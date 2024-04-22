## ¡Bienvenido al repositorio: Dice Game! 🎲🎲

**¡Diviértete con una API completa para un emocionante juego de dados!** 

**¡Comienza la aventura!** 

**¡Prepárate para:**
* **Registrar jugadores:** Crea usuarios con email único y nickname (¡o anónimos!). ‍
* **Lanzar dados:** ¡Emocionantes tiradas para ganar o perder! 
* **Obtener el historial de tiradas:** Consulta tus resultados y porcentaje de éxito. 
* **Administración:** Controla a los jugadores, visualiza estadísticas y rankings. 
* **Seguridad:** La API esta protegida con autenticación por tokens y roles. ️

**¡Manos a la obra!** ️

Utiliza estas rutas para utilizar la API:

**URLs:**
* **POST /players:** Crea un jugador (email único, nickname opcional). ‍
* **PUT /players/{id}:** Modifica el nombre del jugador (por id). 
* **POST /players/{id}/games:** Un jugador lanza los dados (por id). 
* **DELETE /players/{id}/games:** Elimina las tiradas del jugador (por id). ️
* **GET /players:** Obtiene el listado de jugadores con porcentaje medio de éxito. 
* **GET /players/{id}/games:** Obtiene las tiradas de un jugador (por id). 
* **GET /players/ranking:** Obtiene el ranking de porcentaje medio de éxito. 
* **GET /players/ranking/loser:** Obtiene al jugador con peor porcentaje de éxito. 
* **GET /players/ranking/winner:** Obtiene al jugador con mejor porcentaje de éxito. 

**Seguridad:**
* Contiene autenticación por Passport en todas las URLs. 
* Control de roles y restricción de acceso a rutas según privilegios. ⛔️

**Testing:**
* Encontraras tambien tests unitarios de integración con TDD para cada ruta. 

**Te garantizamos:****
* **Diversión sin límites:** ¡Disfruta de un juego de dados emocionante y desafiante! 
* **Control total:** Crea y administra usuarios, visualiza estadísticas y rankings. 
* **Seguridad garantizada:** Protege tu API con autenticación por tokens y roles. ️
* **Código robusto y probado:** Tests unitarios de integración aseguran el correcto funcionamiento. 

**¡Emprende tu viaje y a jugar se ha dicho!** 
