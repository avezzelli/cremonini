<?php

namespace zecchini;

/** CLASSI MODEL **/
require_once 'model/MyObject.php';
require_once 'model/Utente.php';
require_once 'model/Brand.php';
require_once 'model/Cantiere.php';
require_once 'model/Collaudo.php';
require_once 'model/CantiereCollaudo.php';
require_once 'model/GruppoVoce.php';
require_once 'model/Voce.php';
require_once 'model/Commento.php';
require_once 'model/Azienda.php';
require_once 'model/AziendaCantiere.php';
require_once 'model/UtenteC.php';
require_once 'model/Cliente.php';
require_once 'model/RuoloC.php';
require_once 'model/UtentecRuoloc.php';
require_once 'model/Visibilita.php';
require_once 'model/CollaudoRuoloc.php';
require_once 'model/Log.php';
require_once 'model/ResponsabileCollaudo.php';

/** CLASSI DAO **/
require_once 'DAO/ObjectDAO.php';
require_once 'DAO/BrandDAO.php';
require_once 'DAO/CantiereDAO.php';
require_once 'DAO/CollaudoDAO.php';
require_once 'DAO/CantiereCollaudoDAO.php';
require_once 'DAO/GruppoVoceDAO.php';
require_once 'DAO/VoceDAO.php';
require_once 'DAO/CommentoDAO.php';
require_once 'DAO/AziendaDAO.php';
require_once 'DAO/AziendaCantiereDAO.php';
require_once 'DAO/UtenteDAO.php';
require_once 'DAO/UtenteCDAO.php';
require_once 'DAO/ClienteDAO.php';
require_once 'DAO/RuoloCDAO.php';
require_once 'DAO/UtentecRuolocDAO.php';
require_once 'DAO/VisibilitaDAO.php';
require_once 'DAO/CollaudoRuolocDAO.php';
require_once 'DAO/LogDAO.php';
require_once 'DAO/ResponsabileCollaudoDAO.php';

/** CLASSI CONTROL **/
require_once 'controller/AziendaController.php';
require_once 'controller/BrandController.php';
require_once 'controller/CantiereController.php';
require_once 'controller/CollaudoController.php';
require_once 'controller/UtenteController.php';

/** CLASSI VIEW **/
require_once 'view/PrinterView.php';
require_once 'view/BrandView.php';
require_once 'view/AziendaView.php';
require_once 'view/CollaudoView.php';
require_once 'view/CantiereView.php';

//DOMPDF
require_once 'dompdf/autoload.inc.php';
require_once 'dompdf/lib/html5lib/Parser.php';
require_once 'dompdf/lib/php-font-lib/src/FontLib/Autoloader.php';
require_once 'dompdf/lib/php-svg-lib/src/autoload.php';
require_once 'dompdf/src/Autoloader.php';
\Dompdf\Autoloader::register();