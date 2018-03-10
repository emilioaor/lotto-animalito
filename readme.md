Lotto Animalito
=============================================

Página web donde los usuarios pueden jugar a la lotería de animalitos. Se manejan saldos y pagos por transferencia

Niveles de usuarios:
--------------------------------------------------------------------------------

#### Admin:

* Registrar resultados
* Validar y aprobar transferencias
* Validar y aprobar retiros
* Generar reporte diario

#### Normal:
* Registra tickets
* Genera notificaciones de transferencia
* Genera notificaciones de retiro de saldo
* Configuración de datos bancarios
* Consulta de resultados 


Instalación
------------------------------------------------------------------------------
1. Renombrar el archivo **.env.example** por **.env** en la raíz del proyecto

2. En la raíz del proyecto ejecuta el siguiente comando para instalar dependencias de backend

        composer install

3. En la raíz del proyecto ejecuta el siguiente comando para instalar las dependencias de frontend

        npm install

4. Configurar los datos de conexion a la base de datos

5. Ejecutar el siguiente comando para generar la base de datos:

        php artisan migrate --seed

    Esto va a generar la base de datos con toda la data inicial. En el archivo **database/seeds/UserSeeder.php** se encuentran definidos los usuarios por default, siéntete libre de modificar y agregar los usuarios que necesites. Si ya habías corrido el comando anterior puedes reiniciar la base de datos con el siguiente comando

        php artisan migrate:refresh --seed

6. Puedes probar facilmente la aplicación con el siguiente comando:

        php artisan serve

    Con esto te puedes conectar por la siguiente url: **http://localhost:8000**
