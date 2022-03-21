# SaltoWeb
manager Push Notification

https://symfony.com/doc/current/security.html

https://symfony.com/doc/current/security/form_login_setup.html

Registrando Usuarios
1) In RegistrationController::verifyUserEmail():
    * Customize the last redirectToRoute() after a successful email verification.
    * Make sure you're rendering success flash messages or change the $this->addFlash() line.
2) Review and customize the form, controller, and templates as needed.
3) Run "php bin/console make:migration" to generate a migration for the newly added User::isVerified property.

Agregando usuarios 

No ha funcionado como esperaba, pueden ser distintos motivos

Aun así, y por una cuestión profesional también, la idea es migrar a nodejs y express

AUnque no sé como manejar usuarios en esa plataforma.
