<?php 

/********** SHORTCODE *************/
//Cliente
add_shortcode('clienteInserimento', 'addPageClienteInserimento');
function addPageClienteInserimento(){
	include 'pages/cliente/save.php';
}
add_shortcode('clienteDettaglio', 'addPageClienteDettaglio');
function addPageClienteDettaglio(){
	include 'pages/cliente/details.php';
}
add_shortcode('clienteSearch', 'addPageClienteSearch');
function addPageClienteSearch(){
	include 'pages/cliente/search.php';
}

//Cantierista
add_shortcode('utentecInserimento', 'addPageCantieristaInserimento');
function addPageCantieristaInserimento(){
	include 'pages/utentec/save.php';
}
add_shortcode('utentecDettaglio', 'addPageCantieristaDettaglio');
function addPageCantieristaDettaglio(){
	include 'pages/utentec/details.php';
}
add_shortcode('utentecSearch', 'addPageCantieristaSearch');
function addPageCantieristaSearch(){
	include 'pages/utentec/search.php';
}

//Azienda
add_shortcode('aziendaInserimento', 'addPageAziendaInserimento');
function addPageAziendaInserimento(){
	include 'pages/azienda/save.php';
}
add_shortcode('aziendaDettaglio', 'addPageAziendaDettaglio');
function addPageAziendaDettaglio(){
	include 'pages/azienda/details.php';
}
add_shortcode('aziendaSearch', 'addPageAziendaSearch');
function addPageAziendaSearch(){
	include 'pages/azienda/search.php';
}

//Brand
add_shortcode('brandInserimento', 'addPageBrandInserimento');
function addPageBrandInserimento(){
	include 'pages/brand/save.php';
}
add_shortcode('brandDettaglio', 'addPageBrandDettaglio');
function addPageBrandDettaglio(){
	include 'pages/brand/details.php';
}
add_shortcode('brandSearch', 'addPageBrandSearch');
function addPageBrandSearch(){
	include 'pages/brand/search.php';
}

//Collaudo
add_shortcode('collaudoInserimento', 'addPageCollaudoInserimento');
function addPageCollaudoInserimento(){
	include 'pages/collaudo/save.php';
}
add_shortcode('collaudoDettaglio', 'addPageCollaudoDettaglio');
function addPageCollaudoDettaglio(){
	include 'pages/collaudo/details.php';
}
add_shortcode('collaudoSearch', 'addPageCollaudoSearch');
function addPageCollaudoSearch(){
	include 'pages/collaudo/search.php';
}

//Cantiere
add_shortcode('cantiereInserimento', 'addPageCantiereInserimento');
function addPageCantiereInserimento(){
	include 'pages/cantiere/save.php';
}
add_shortcode('cantiereDettaglio', 'addPageCantiereDettaglio');
function addPageCantiereDettaglio(){
	include 'pages/cantiere/details.php';
}
add_shortcode('cantiereSearch', 'addPageCantiereSearch');
function addPageCantiereSearch(){
	include 'pages/cantiere/search.php';
}


?>