<?php
	session_start();
	
	include_once '../config/database.php';

	//referenciar o DomPDF com namespace
	use Dompdf\Dompdf;
	use Dompdf\Options;

	include_once("conexao.php");	
	//include_once("..\config\database.php");	

	if(isset($_POST['event-certified-btn'])) {  
	
	
	$location = isset($_POST['check']) ? $_POST['check'] : '';
	$event = isset($_POST['event']) ? $_POST['event'] : '';
	$name_event = "event";

	$result_transacoes = "SELECT * From user where cpf = ".$location[0].";";
	$resultado_trasacoes = mysqli_query($conn, $result_transacoes);
	$row_transacoes = mysqli_fetch_assoc($resultado_trasacoes);	
	
	$result_event_transacoes = "SELECT * From ".$name_event." where id = ".$event.";";
	$resultado_event_trasacoes = mysqli_query($conn, $result_event_transacoes);
	$row_event_transacoes = mysqli_fetch_assoc($resultado_event_trasacoes);

	$html = '<h1 style="text-align: center; font-size:40px !important;">'.$row_transacoes['name'].' </h1>';	
	$html .= '<p style="text-align: center;">Concluiu o curso de <strong>'.$row_event_transacoes['name'].'</strong> de <strong>100</strong> horas, no período de 01 de junho a 31 de agosto de 2019.<br><br><br></p>';
	$html .= '<p style="text-align: center;">___________________________________________________<br>Diretor da unidade de ensino</p>';
	
	// include autoloader
	require_once("dompdf/autoload.inc.php");
	
	$options = new Options();
	$options->setIsRemoteEnabled(true);

	//Criando a Instancia
	$dompdf = new DOMPDF();
	$dompdf->setOptions($options);
	$dompdf->setPaper('A4','landscape');

		// Carrega seu HTML
	$dompdf->load_html('
	<section style= "border: 6px solid red;">
	<img src="../../../media/logo.jpg" height="100" width="350" style="margin-left:5%; margin-top:1%;">
	<h1 style="text-align: center; font-size:60px !important;">Certificado de Conclusão</h1>
	<h3 style="text-align: center; font-size:40px !important; ">O instituto IIDS de Ensino</h3>
	<p style="text-align: center;">Certifica que</p>
	'. $html .'
	</section>
	');

	//Renderizar o html
	$dompdf->render();

	//Exibibir a página
	$dompdf->stream(
		"relatorio_celke.pdf", 
		array(
			"Attachment" => false //Para realizar o download somente alterar para true
		)
	);
	//header('location: ../../event/?event='.$event);
	}
?>