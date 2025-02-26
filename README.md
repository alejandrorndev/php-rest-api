# php-rest-api

Esta es una API RESTful desarrollada en PHP con Slim Framework que implementa un CRUD completo para la gestión de tareas. La API permite listar, crear, actualizar y eliminar tareas a través de endpoints específicos.

## Características

- **CRUD Completo:** Permite crear, leer, actualizar y eliminar tareas.
- **Endpoints:** Rutas definidas para cada operación.
- **JSON como formato de entrada y salida.**

## Endpoints

### Tareas
- **Listar Tareas**
  - **Método:** GET
  - **Ruta:** `/api/tasks/list`
  - **Descripción:** Obtiene la lista de todas las tareas.

- **Crear Tarea**
  - **Método:** POST
  - **Ruta:** `/api/tasks/create`
  - **Descripción:** Crea una nueva tarea.
  - **Ejemplo de cuerpo (JSON):**
    ```json
    {
      "title": "Nueva Tarea",
      "description": "Descripción de la tarea",
      "status": "pendiente"
    }
    ```

- **Actualizar Tarea**
  - **Método:** PUT
  - **Ruta:** `/api/tasks/{id}`
  - **Descripción:** Actualiza los datos de una tarea específica, se puede enviar un solo dato o todos.
  - **Ejemplo de cuerpo (JSON):**
    ```json
    {
      "title": "Tarea Actualizada",
      "description": "Descripción actualizada",
      "status": "completada"
    }
    ```

- **Eliminar Tarea**
  - **Método:** DELETE
  - **Ruta:** `/api/tasks/delete/{id}`
  - **Descripción:** Elimina una tarea específica.

## Requisitos

- PHP 8.1 o superior
- Composer
- Slim Framework 4
- PostgreSQL (o la base de datos que prefieras)
- Docker y Docker Compose (opcional, para despliegue en contenedores)

## Instalación

1. **Clonar el repositorio:**
   ```bash
   git clone https://github.com/tuusuario/task-crud-api.git
   cd task-crud-api
