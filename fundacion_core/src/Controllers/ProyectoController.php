<?php

namespace App\Controllers;



use App\Models\Proyecto;
use App\Models\ProyectoMedia;
use App\Helpers\AuthHelper;

class ProyectoController
{
    private $proyectoModel;
    private $mediaModel;
    private $authHelper;
    private $uploadDir;

    public function __construct()
    {
        // CRÍTICO: Usamos UPLOAD_DIR_PATH definida en bootstrap.php
        // Esto apunta a /home/duban/fundacion_core/shared_uploads/
        $this->uploadDir = UPLOAD_DIR_PATH;

        $this->proyectoModel = new Proyecto();
        $this->mediaModel = new ProyectoMedia();
        $this->authHelper = new AuthHelper();

        // Asegurar que la carpeta exista con permisos correctos
        if (!is_dir($this->uploadDir)) {
            mkdir($this->uploadDir, 0775, true);
        }
    }

    /**
     * Obtiene la lista de proyectos para el admin (Solo los no eliminados).
     */
    public function index()
    {
        $this->authHelper->requireLogin();
        return $this->proyectoModel->findAllActive();
    }

    /**
     * Sube un archivo de media (Imagen o Video) y devuelve el nombre de archivo único.
     * @param array $file Array de un solo archivo ($_FILES['nombre']['...'])
     * @param bool $isPrincipal Indica si es la imagen principal (diferente límite/validación)
     * @return string|false Nombre del archivo o false en caso de error.
     */
    private function handleFileUpload(array $file, bool $isPrincipal = false)
    {
        if ($file['error'] !== UPLOAD_ERR_OK) {
            return false;
        }

        $allowedTypes = [
            'image/jpeg', 'image/png', 'image/gif', 
            'video/mp4', 'video/webm', 'video/ogg'
        ];

        if (!in_array($file['type'], $allowedTypes)) {
            return false;
        }

        $ext = pathinfo($file['name'], PATHINFO_EXTENSION);
        $fileName = uniqid('media_', true) . '.' . $ext;
        $targetPath = $this->uploadDir . $fileName;

        if (move_uploaded_file($file['tmp_name'], $targetPath)) {
            return $fileName;
        }

        return false;
    }

    /**
     * Procesa múltiples archivos de media.
     * @param int $proyectoId ID del proyecto creado.
     * @param array $files Array reestructurado de archivos ($files[i]['name']).
     * @return bool True si todos se procesaron sin errores críticos, false si hubo un fallo de BD.
     */
    private function handleMultipleMediaUploads(int $proyectoId, array $files)
    {
        $all_success = true;

        foreach ($files as $file) {
            // Determinar si es imagen o video para guardar en BD
            $tipo = (strpos($file['type'], 'image/') !== false) ? 'imagen' : 'video';

            // Subir y obtener el nombre único
            $fileName = $this->handleFileUpload($file);

            if ($fileName) {
                // Guardar la referencia en la tabla proyecto_media
                $result = $this->mediaModel->saveMedia($proyectoId, $fileName, $tipo);
                if (!$result) {
                    // Si falla la inserción en BD, intentamos borrar el archivo subido
                    @unlink($this->uploadDir . $fileName);
                    $all_success = false;
                }
            } else {
                // Fallo en la subida (ej. archivo muy grande o tipo incorrecto)
                $all_success = false;
            }
        }
        return $all_success;
    }


    /**
     * Crea un nuevo proyecto.
     * @param array $data Datos del formulario (nombre, descripcion, estado)
     * @param array $files Archivos de imagen (de $_FILES, incluye 'imagen' y 'multimedia')
     * @return bool|string True si es éxito, string con mensaje de error si falla.
     */
    public function store(array $data, array $files)
    {
        $adminId = $_SESSION['admin_id'] ?? null;
        if (!$adminId) {
            return "Error: Sesión de administrador no encontrada.";
        }

        $imageFileName = null;
        $multimediaFiles = [];
        $validationError = null;

        // 1. Validar campos obligatorios de texto antes de la subida
        $nombre = trim($data['nombre'] ?? '');
        $descripcion = trim($data['descripcion'] ?? '');
        $estado = $data['estado'] ?? 'activo';

        if (empty($nombre) || empty($descripcion)) {
            return "El nombre y la descripción son obligatorios.";
        }

        // 2. Manejo de Imagen Principal
        if (!empty($files['imagen']['name'])) {
            $imageFileName = $this->handleFileUpload($files['imagen'], true);
            if (!$imageFileName) {
                // Si la subida falla, guardamos el error
                $validationError = "Error al subir la Imagen Principal. Asegúrate de que es JPG, PNG o GIF, y que no excede el límite de tamaño.";
            }
        }

        // Si la imagen principal falló, detenemos el proceso aquí.
        if ($validationError) {
            return $validationError;
        }

        // 3. Reestructurar archivos múltiples (multimedia)
        // Se ejecuta solo si hay archivos subidos en el campo 'multimedia[]'
        if (isset($files['multimedia']) && !empty($files['multimedia']['name'][0])) {
            $multimediaFiles = $this->rearrangeFilesArray($files['multimedia']);
        }


        // 4. Preparación de Datos para la BD (Tabla `proyectos`)
        $proyectoData = [
            'nombre' => $nombre,
            'descripcion' => $descripcion,
            'estado' => $estado,
            'imagen_principal' => $imageFileName, // Puede ser NULL si no se subió
        ];


        // 5. Creación en BD (Tabla `proyectos`)
        $proyectoId = $this->proyectoModel->create($proyectoData);

        if ($proyectoId) {
            // 6. Guardar Multimedia Adicional (Tabla `proyecto_media`)
            if (!empty($multimediaFiles)) {
                $mediaSuccess = $this->handleMultipleMediaUploads($proyectoId, $multimediaFiles);

                // Opcional: Si $mediaSuccess es false, puedes retornar un error de advertencia,
                // pero como el proyecto base ya fue creado, muchos prefieren dejarlo pasar
                // con un log de error. Dejamos el retorno como true para indicar que el proyecto se creó.
            }
            return true;
        } else {
            // Error de BD al crear el proyecto

            // 7. Limpieza de archivos subidos si falla la BD
            if ($imageFileName) {
                // Si el archivo principal se subió, pero la BD falló, lo borramos.
                @unlink($this->uploadDir . $imageFileName);
            }


            return "Error al guardar el proyecto en la base de datos.";
        }
    }

    private function rearrangeFilesArray(array $file_post): array
    {
        $file_array = [];
        $file_keys = array_keys($file_post);

        for ($i = 0; $i < count($file_post['name']); $i++) {
            foreach ($file_keys as $key) {
                $file_array[$i][$key] = $file_post[$key][$i];
            }
        }
        return $file_array;
    }

    /**
     * Busca un proyecto por ID.
     * @param int $id
     * @return array|false
     */
    public function find(int $id): ?array // Modificado para usar findWithAllData
    {
        $this->authHelper->requireLogin();
        return $this->proyectoModel->findWithAllData($id);
    }

    /**
     * Actualiza un proyecto existente.
     * @param int $id ID del proyecto a actualizar.
     * @param array $data Datos del formulario.
     * @param array $file Archivo de imagen (de $_FILES)
     * @return bool|string True si es éxito, string con mensaje de error si falla.
     */
    public function update(int $id, array $data, array $files) //  Aceptar todo $_FILES
    {
        $this->authHelper->requireLogin();
        $adminId = $_SESSION['admin_id'] ?? null;
        if (!$adminId) {
            return "Error: Sesión de administrador no encontrada.";
        }

        // 1. Obtener datos existentes (usamos el find simple de Model para evitar bucles)
        $proyectoExistente = $this->proyectoModel->find($id);
        if (!$proyectoExistente) {
            return "Proyecto no encontrado.";
        }

        // 2. Preparar datos de texto y actualizar_at
        $updateData = [
            'nombre' => trim($data['nombre']),
            'descripcion' => trim($data['descripcion']),
            'estado' => $data['estado'] ?? 'activo',
            'updated_at' => date('Y-m-d H:i:s'),
        ];
        $imageFileName = $proyectoExistente['imagen_principal'];

        // 3. Manejo de Subida de Nueva Imagen Principal
        if (!empty($files['imagen']['name'])) {
            $newImageFileName = $this->handleFileUpload($files['imagen'], true);
            if (!$newImageFileName) {
                return "Error al subir la nueva imagen principal.";
            }

            // Borrar imagen antigua si existe
            if ($imageFileName && file_exists($this->uploadDir . $imageFileName)) {
                @unlink($this->uploadDir . $imageFileName);
            }
            $imageFileName = $newImageFileName;
        }
        $updateData['imagen_principal'] = $imageFileName;

        // 4. Actualización en la BD (Tabla `proyectos`)
        $success = $this->proyectoModel->update($id, $updateData);

        if (!$success) {
            return "Error al actualizar los datos principales del proyecto.";
        }

        // 5. Manejo de Subida de Multimedia Adicional
        if (isset($files['multimedia']) && !empty($files['multimedia']['name'][0])) {
            $multimediaFiles = $this->rearrangeFilesArray($files['multimedia']);
            // Reutilizamos el método de subir múltiples
            $this->handleMultipleMediaUploads($id, $multimediaFiles);
        }

        return true;
    }


    /**
     * Borrado Físico de un solo archivo multimedia
     */
    public function deleteMedia(int $mediaId)
    {
        $this->authHelper->requireLogin();

        $mediaFile = $this->mediaModel->find($mediaId);
        if (!$mediaFile) {
            return "Archivo de multimedia no encontrado.";
        }

        // 1. Borrado lógico en BD
        $success = $this->mediaModel->update($mediaId, ['deleted_at' => date('Y-m-d H:i:s')]);

        if ($success) {
            // 2. Borrado físico del archivo
            $filePath = $this->uploadDir . $mediaFile['ruta'];
            if (file_exists($filePath)) {
                @unlink($filePath);
            }
            return true;
        }
        return "Error al realizar el borrado en la base de datos.";
    }

    /**
     * Borrado Físico total del proyecto y todos sus archivos
     */
    public function destroy(int $id)
    {
        $this->authHelper->requireLogin();

        // 1. Obtener datos antes de "borrar" la referencia en BD
        $dataToDelete = $this->proyectoModel->findWithAllData($id);
        if (!$dataToDelete) {
            return "Proyecto no encontrado.";
        }

        $proyecto = $dataToDelete['proyecto'];
        $multimedia = $dataToDelete['multimedia'];

        // 2. Borrado lógico en BD (Proyecto y Media masiva)
        $successProject = $this->proyectoModel->update($id, [
            'deleted_at' => date('Y-m-d H:i:s'),
            'estado' => 'finalizado'
        ]);
        $this->mediaModel->softDeleteByProyectoId($id);

        if ($successProject) {
            // 3. BORRADO FÍSICO DE ARCHIVOS

            // A. Imagen Principal
            if (!empty($proyecto['imagen_principal'])) {
                $pathPrincipal = $this->uploadDir . $proyecto['imagen_principal'];
                if (file_exists($pathPrincipal)) @unlink($pathPrincipal);
            }

            // B. Carrusel Multimedia
            foreach ($multimedia as $media) {
                $pathMedia = $this->uploadDir . $media['ruta'];
                if (file_exists($pathMedia)) @unlink($pathMedia);
            }

            return true;
        }
        return "Error al procesar la eliminación.";
    }
}
