<?php
session_start();

include_once '../config/database.php';
include_once '../model/user.php';
include_once '../controller/user.php';
include_once '../model/certified.php';
include_once '../controller/certified.php';
include_once '../model/event.php';
include_once '../controller/event.php';

$database = new Database();
$conn = $database->getConn();

//referenciar o DomPDF com namespace
use Dompdf\Dompdf;
use Dompdf\Options;

if (isset($_POST['event-certified-btn'])) {

	$userController = new UserController();
	$eventController = new EventController();

	$location = isset($_POST['check']) ? $_POST['check'] : '';
	$event = isset($_POST['event']) ? $_POST['event'] : '';
	$name_event = "event";

	$event = $eventController->getEvent($conn, new Event($event, null, null, null, null, null, null, null, null));

	foreach ($location as $user_cpf) {
		$user = new User($user_cpf, null, null, null, null, null, null, null);
		$user = $userController->getUserInformation($user, $conn);

		$html = '<h1 style="text-align: center; font-size:40px !important;">' . $user->getName() . ' </h1>';
		$html .= '<p style="text-align: center;">Concluiu o curso de <strong>' . $event->getName() . '</strong> de <strong>100</strong> horas, no período de 01 de junho a 31 de agosto de 2019.<br><br><br></p>';
		$html .= '<p style="text-align: center;">___________________________________________________<br>Diretor da unidade de ensino</p>';

		// include autoloader
		require_once("dompdf/autoload.inc.php");

		$options = new Options();
		$options->setIsRemoteEnabled(true);

		//Criando a Instancia
		$dompdf = new DOMPDF();
		$dompdf->setOptions($options);
		$dompdf->setPaper('A4', 'landscape');

		// Carrega seu HTML
		$dompdf->load_html('
		<section style= "border: 6px solid red;">
		<img src="../../../media/logo.jpg" height="100" width="350" style="margin-left:5%; margin-top:1%;">
		<h1 style="text-align: center; font-size:60px !important;">Certificado de Conclusão</h1>
		<h3 style="text-align: center; font-size:40px !important; ">O instituto IIDS de Ensino</h3>
		<p style="text-align: center;">Certifica que</p>
		' . $html . '
		</section>
		');

		//Renderizar o html
		$dompdf->render();

		//Exibibir a página
		$output = $dompdf->output();
		$filename = md5(time());
		file_put_contents('pdf/' . $filename . '.pdf', $output);

		$certifiedController = new CertifiedController();
		$certified = new Certified($user->getCpf(), $event->getId(), $filename);

		$certifiedController->insertCertified($conn, $certified);
	}

	header('location: ../../event/?event=' . $event->getId());
}
