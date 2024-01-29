<?php
// utilisation des sessions
session_start();

include "moteurtemplate.php";
include "connect.php";

include "Controllers/membresController.php";

include "Controllers/projetsController.php";
include "Controllers/coursController.php";
include "Controllers/tagsController.php";
include "Controllers/categorieController.php";

include "Controllers/commentaireController.php";

include "Controllers/attribuerController.php";
include "Controllers/contribuerController.php";
include "Controllers/realiserController.php";

$memController = new MembreController($bdd,$twig);

$projetsController = new ProjetsController($bdd,$twig);
$coursController = new CoursController($bdd,$twig);
$tagsController = new TagsController($bdd,$twig);
$categorieController = new CategorieController($bdd,$twig);

$commentaireController = new CommentaireController($bdd,$twig);

$attribuerController = new AttribuerController($bdd,$twig);
$contribuerController = new ContribuerController($bdd,$twig);
$realiserController = new RealiserController($bdd,$twig);


// ============================== connexion / deconnexion - sessions ==================

// si les variables de session n'existent pas, on les créer
if (!isset($_SESSION['acces']) || (isset($_SESSION['acces']) && $_SESSION['acces'] == "non") ) {
   $_SESSION['acces']="non";
   $_SESSION['prenom'] = "doe";
   $_SESSION['idmembre'] = 1;
}

// bouton se souvenir de moi connexion
if (!isset($_SESSION['acces']) && (isset($_SESSION['acces']) && $_SESSION['acces'] == "non") && isset($_COOKIE['idmembre']) )  {  
  $memController->membreConnexion($_POST);  
}

// click sur le bouton connexion
if (isset($_POST["connexion"]))  {  
  $memController->membreConnexion($_POST); 
}

// click sur le bouton inscription
if (isset($_POST["inscription"]))  {  
 $memController->membreInscription($_POST);  
}

// deconnexion : click sur le bouton deconnexion
if (isset($_GET["action"]) && $_GET['action']=="logout") { 
  $memController->membreDeconnexion(); 
 } 

// formulaire de connexion
if (isset($_GET["action"])  && $_GET["action"]=="login") {
  $memController->membreFormulaire(); 
}

// formulaire d'inscription
if (isset($_GET["action"])  && $_GET["action"]=="Signup") {
  $memController->membreFormulaireInscription(); 
}

// ============================== page d'accueil ==================

// cas par défaut = page d'accueil
if (!isset($_GET["action"]) && empty($_POST)) {
  echo $twig->render('index.html.twig',array('acces'=> $_SESSION['acces'], 'prenom'=> $_SESSION['prenom'])); 
}

// ============================gestion des membres =====================

// formulaire ajout d'un utilisateur : saisie des caractéristiques à ajouter dans la BD
if (isset($_GET["action"]) && $_GET["action"]=="ajout-utilisateur") {
  $memController->AjoutMembreFormulaire(); 
}

// click sur le bouton ajouter un utilisateur + redirection sur espace membre
if (isset($_POST["ajout-membre"]))  {  
  $memController->membreAjout($_POST); 
  $memController->listeInfosMembre();
}

// supression d'un membre dans la base de donnée + redirection sur espace membre
if (isset($_POST["supp_membre"])) { 
  $memController->suppMembre();
  $memController->listeInfosMembre();
}

// modification de ses informations : saisie des nouvelles valeurs dans un formulaire
if (isset($_GET["action"]) && $_GET["action"]=="profil_modif") {   
  $memController->membreFormulaireModif();
}
// modification de ses informations : saisie des nouvelles valeurs dans un formulaire
if (isset($_POST["membre_modif"])) {   
  $memController->membreFormModif();
}

// click bouton valider la modification d'un membre
if (isset($_POST["mod_membre"])) { 
  $memController->modMembre();
  $memController->listeInfosMembre();
}

// ============================== gestion des projets ==================

// liste des projets dans un tableau HTML (chat gpt + mes modifications)
if (isset($_GET["action"]) && $_GET["action"] == "liste") {
  // Déterminez la page actuelle
  $currentPage = isset($_GET['page']) ? (int)$_GET['page'] : 1;

  // Appelez la méthode pour afficher la liste des projets en passant la page actuelle
  $projetsController->listeProjets($currentPage);
}

// détails des projets au click
if (isset($_POST["details"])) {
  $projetsController->listeDetailsProjets();
}

// affiche les projets du membre connecté + ses infos >> MON ESPACE
if (isset($_GET["action"]) && $_GET["action"]=="mesinfos") { 
  $memController->listeInfosMembre();
}

// valide le projet au click
if (isset($_POST["valider_"])) { 
  $projetsController->valideProjet();
  $memController->listeInfosMembre();
}

// formulaire ajout d'un projet : saisie des caractéristiques à ajouter dans la BD
if (isset($_GET["action"]) && $_GET["action"]=="ajout") {
  $projetsController->formAjoutProjet();
 }

// ajout du projet dans la base de donnée + redirection espace membre
if (isset($_POST["valider_ajout"])) {
  $projetsController->ajoutProjet();
  $memController->listeInfosMembre();
}

// supression d'un projet dans la base de données + redirection espace membre
if (isset($_POST["valider_supp"])) { 
  $projetsController->suppProjet();
  $memController->listeInfosMembre();
}

// modification d'un projet : saisie des nouvelles valeurs dans un formulaire
//  ==> version 0 : pas modif de l'id_projet ?
if (isset($_POST["saisie_modif"])) {   
  $projetsController->saisieModProjet();
}

//modification d'un projet : enregistrement dans la bd + redirection espace membre
// --> au clic sur le bouton "valider_modif" du form précédent
if (isset($_POST["valider_modif"])) {
  $projetsController->modProjet();
  $memController->listeInfosMembre();
}


// recherche d'un projet : saisie des critères de recherche dans un formulaire
if (isset($_GET["action"]) && $_GET["action"]=="recher") {
  $projetsController->formRechercheProjet();
}

// recherche des projets : construction de la requete SQL en fonction des critères 
// de recherche et affichage du résultat dans un tableau HTML 
// >> Code de Mr Pécatte
if (isset($_GET["action"]) && $_GET["action"]=="ajax" && isset($_GET["motcle"])) {
  $projetsController->rechercheProjetAJAX($_GET["motcle"]);
}

// ============================== gestion des cours ==================

// formulaire ajout d'un cours : saisie des caractéristiques à ajouter dans la BD
if (isset($_GET["action"]) && $_GET["action"]=="ajout-cours") {
  $coursController->AjoutCoursFormulaire(); 
 }

// click sur le bouton ajouter cours + redirection sur espace membre
if (isset($_POST["valider-ajout-cours"]))  {  
  $coursController->coursAjout($_POST);  
  $memController->listeInfosMembre();
 }

// supression d'un cours dans la base de données + redirection sur espace membre
if (isset($_POST["supp_cours"])) { 
  $coursController->suppCours();
  $memController->listeInfosMembre();
}

// modification des cours: saisie des nouvelles valeurs dans un formulaire
if (isset($_POST["cours_modif"])) {   
  $coursController->coursFormModif();
}

// click bouton valider la modification d'un cours
if (isset($_POST["mod_cours"])) { 
  $coursController->modCours();
  $memController->listeInfosMembre();
}


// ============================== gestion des catégories ==================

// formulaire ajout d'une catégorie : saisie des caractéristiques à ajouter dans la BD
if (isset($_GET["action"]) && $_GET["action"]=="ajout-categorie") {
  $categorieController->AjoutCategorieFormulaire(); 
 }

// click sur le bouton ajouter catégorie + redirection sur espace membre
if (isset($_POST["valider-ajout-categorie"]))  {  
  $categorieController->categorieAjout($_POST);  
  $memController->listeInfosMembre();
 }

// supression d'une catégorie dans la base de données + redirection sur espace membre
if (isset($_POST["supp_categorie"])) { 
  $categorieController->suppCategorie();
  $memController->listeInfosMembre();
}

// modification des catégories: saisie des nouvelles valeurs dans un formulaire
if (isset($_POST["cat_modif"])) {   
  $categorieController->categorieFormModif();
}

// click bouton valider la modification d'une catégorie
if (isset($_POST["modif_cat"])) { 
  $categorieController->modCategorie();
  $memController->listeInfosMembre();
}


// ============================== gestion des commentaires ==================

// click sur le bouton ajouter un commentaire + redirection sur l'onglet "Projets"
if (isset($_POST["valider-ajout-commentaire"]))  {  
  $commentaireController->commentaireAjout($_POST, $_SESSION['idmembre']);  
  $currentPage = isset($_GET['page']) ? (int)$_GET['page'] : 1;
  $projetsController->listeProjets($currentPage);
 }

?>
