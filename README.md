## BACKEND NeuroMind App
### Инструкция по развертыванию приложения
Зайти в корень проект и набрать консольную команду 
#### make start-with-dev-env
Добавить значение хоста процессного приложения в константу PROCESS_APP_HOST, которая находится в .env.local

После этого приложение доступно по адресу http://localhost

### API документация
http://localhost/api/doc - дока с ui
http://localhost/api/doc.json - json версия

### Фичи
src/Service/ProcessCoreService.php - Сервис отвечающий за связь с ML CORE сервисом
src/Service/DocumentGeneratorService.php - Сервис отвечающий генерацию документа docx из текста
src/Controller/AudioController.php - контроллер принимающий все запросы по HTTP API
