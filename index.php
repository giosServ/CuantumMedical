<!DOCTYPE HTML>

<html>

<head>

    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <meta name="Description"
        CONTENT="High performance cyanoacrylate adhesives for Medical Devices and Veterinary and Cosmetic applications">
    <title>Cuantum Medical and Cosmetics</title>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.0/jquery.min.js"></script>
    <link rel="icon" href="imatges/favicon.png?v=3" sizes="16x16" type="image/png">
</head>


<!-- Google tag (gtag.js) -->
<script async src="https://www.googletagmanager.com/gtag/js?id=G-85RBY155B6"></script>
<script>
    window.dataLayer = window.dataLayer || [];
    function gtag() { dataLayer.push(arguments); }
    gtag('js', new Date());

    gtag('config', 'G-85RBY155B6');
</script>

<script>

    var l = navigator.language.substring(0, 2)

    if (['ca', 'es', 'en'].indexOf(l) != -1)
        window.location.replace("index-" + l + ".php" + window.location.search);
    else {
        var found = false;

        if (navigator.languages != null) {
            for (v in navigator.languages) {
                if (['ca', 'es', 'en'].indexOf(navigator.languages[v]) != -1) {
                    window.location.replace("index-" + navigator.languages[v] + ".php" + window.location.search);
                    found = true;
                    break;
                }
            }
        }

        if (found == false)
            window.location.replace("index-en.php" + window.location.search);
    }
</script>

<!-- Cuantum Medical and cosmetics  -->
<!-- Design Curro Astorza Dev. Carles Oriol - 2016 -->

</html>