#!/bin/bash

# Cambia al directorio donde está tu repositorio
cd /var/www/html/api_gastos_v1

# Realiza un pull desde la rama main
#git pull origin main
git pull git@github.com:jnarvaez06/api_gastos_v1.git

# Reinicia tu aplicación si es necesario
# Esto podría variar dependiendo de tu aplicación y cómo esté configurada
# Por ejemplo, si estás utilizando Node.js, podrías necesitar reiniciar el servidor
# con el siguiente comando:
# pm2 restart <nombre_del_proceso>

# Ejecuta otros comandos de post-despliegue si es necesario
# Por ejemplo, instalar nuevas dependencias, compilar assets, etc.
