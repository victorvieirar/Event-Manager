<html>
    <body style="font-family: 'Helvetica'; background-color: #f2f2f2; padding: 15px; margin: 0;">
        <div style="display: flex; flex-direction: row; justify-content: center; padding: 25px; background-color: #0b2437; border-top-left-radius: 5px; border-top-right-radius: 5px;">
            <img style="width: 294.66px; height: 65px;" src="http://iids.com.br/media/logo.png" alt="">            
        </div>
        <div style="padding: 15px; background-color: #eee;">
            <p><?php session_start(); echo $_SESSION['message']; unset($_SESSION['message']) ?></p>
        </div>
        <div style="display: flex; flex-direction: row; justify-content: center; padding: 25px; background-color: rgb(12, 148, 218); border-bottom-left-radius: 5px; border-bottom-right-radius: 5px;">
            <a style="color: white !important; text-decoration: none !important; margin: 0 5px; font-size: 0.9em; font-weight: 400; transition: 100ms ease-in;" href="https://www.facebook.com/Instituto-Integrado-de-Desenvolvimento-em-Sa%C3%BAde-IIDS-1105375109565745/"><i class="fab fa-facebook-f"></i> Nosso Facebook</a>
            <a style="color: white !important; text-decoration: none !important; margin: 0 5px; font-size: 0.9em; font-weight: 400; transition: 100ms ease-in;" href="https://www.instagram.com/iidsintegrada/"><i class="fab fa-instagram"></i> Nosso Instagram</a>
            <a style="color: white !important; text-decoration: none !important; margin: 0 5px; font-size: 0.9em; font-weight: 400; transition: 100ms ease-in;" href="mailto:contato@iids.com.br"><i class="far fa-envelope-open"></i> E-mail</a>
            <span style="color: white !important; text-decoration: none !important; margin: 0 5px; font-size: 0.9em; font-weight: 400; transition: 100ms ease-in;"><i class="fas fa-phone"></i> (84) 99605.5403</span>
        </div>
    </body>
</html>