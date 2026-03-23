<?php
// Paramètres de connexion à la base de données
$host = "127.0.0.1"; // Utiliser 127.0.0.1 au lieu de localhost
$dbname = "users"; // Nom de la base de données
$username = "root"; // Nom d'utilisateur de la base de données
$password = ""; // Mot de passe (vide si tu utilises WAMP/XAMPP sans mot de passe pour root)

try {
    // Connexion à la base de données via PDO
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;port=3306", $username, $password); // Spécifier le port 3306 si nécessaire
    // Définir le mode d'erreur de PDO
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Erreur de connexion : " . $e->getMessage());
}

// Récupérer les données envoyées par le formulaire
$email = $_POST['email'];
$password = $_POST['password'];

// Requête SQL pour récupérer l'utilisateur en fonction de l'email
$stmt = $pdo->prepare("SELECT * FROM users WHERE email = :email LIMIT 1");
$stmt->bindParam(':email', $email);
$stmt->execute();

// Vérifier si l'utilisateur existe
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if ($user) {
    // Comparer le mot de passe envoyé avec celui stocké dans la base de données
    if (password_verify($password, $user['password'])) {
        // Mot de passe correct
        echo "Connexion réussie ! Bienvenue " . htmlspecialchars($user['email']);
        // Par exemple, tu pourrais rediriger l'utilisateur vers une page protégée
        // header('Location: page_protegee.php');
    } else {
        // Mot de passe incorrect
        echo "Mot de passe incorrect !";
    }
} else {
    // Email non trouvé
    echo "Aucun utilisateur trouvé avec cet email !";
}
?>
