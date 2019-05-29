<html>
    <div style="font-family: 'Helvetica', sans-serif; padding: 15px; background-color: rgb(0,192,184);">
        <div style="font-weight: 400;">
            <h3 style="text-transform: uppercase; color: white;">Olá, <?php echo $_GET['name']; ?>!</h3>
            <div style="background-color: white; padding: 15px; border-radius: 3px; color: rgb(50,50,50);">
                <p>Você solicitou uma recuperação de senha.</p>
                <br><p>Sua senha é:<p>
                <div style="background-color: #efefef; padding: 15px; border-radius: 3px; color: rgb(50, 50, 50);">
                    <span style="font-weight: 700;"><?php echo $_GET['password']; ?></span>
                </div>
                <br><p style="text-transform: uppercase; font-size: 0.85rem;">Obrigado! 😄</p>
            </div>
        </div>
        <div>
            <img src="http://iids.com.br/media/symbol.png" style="margin: 15px; width: 50px;">
            <a style="text-transform: uppercase; color: white !important; text-decoration: none; margin-left: 20px;" href="https://www.facebook.com/iids.integrada.35">Facebook</a>
            <a style="text-transform: uppercase; color: white !important; text-decoration: none; margin-left: 20px;" href="https://www.instagram.com/iidsintegrada/">Instagram</a>
            <a style="text-transform: uppercase; color: white !important; text-decoration: none; margin-left: 20px;" href="https://iids.com.br/">IIDS</a>
        </div>
    </div>
</html>