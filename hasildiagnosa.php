<?php
/**
 * Hasil Diagnosa — Diagnosis Results Detail Page
 *
 * Shows the full detail of a specific damage type, including symptoms
 * and recommended repair steps.
 *
 * Previously ran the same query 5 times to get 5 different fields.
 * Now queries once and reuses the result.
 */
require_once 'includes/database.php';
require_once 'includes/auth.php';
require_once 'includes/diagnosis_engine.php';

$activePage = 'diagnosa';

$damageId = $_GET['id'] ?? '';
if (empty($damageId)) {
    header("Location: diagnosa.php");
    exit;
}

$damage   = fetchDamageById($db, $damageId);
$symptoms = fetchSymptomsForDamage($db, $damageId);

if ($damage === null) {
    header("Location: diagnosa.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <title>Hasil Diagnosa | Kumaphone</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Detail hasil diagnosa kerusakan smartphone">
    <link rel="stylesheet" href="https://unpkg.com/flowbite@1.5.4/dist/flowbite.min.css">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="icon" href="img/2.png">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Inter', sans-serif !important;
            -webkit-user-select: none;
            -moz-user-select: none;
            -ms-user-select: none;
            user-select: none;
        }
    </style>
</head>
<body style="background-color:#101927;">

<?php include 'includes/navigation.php'; ?>

<!-- Breadcrumb -->
<nav class="flex px-5 py-3 text-gray-700 border border-gray-200 rounded-lg bg-gray-50 dark:bg-gray-800 dark:border-gray-700" aria-label="Breadcrumb">
    <ol class="inline-flex items-center space-x-1 md:space-x-3">
        <li class="inline-flex items-center">
            <a href="index.html" class="inline-flex items-center text-sm font-medium text-gray-700 hover:text-blue-600 dark:text-gray-400 dark:hover:text-white">
                <svg aria-hidden="true" class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20"><path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z"></path></svg>
                Halaman Utama
            </a>
        </li>
        <li>
            <div class="flex items-center">
                <svg aria-hidden="true" class="w-6 h-6 text-gray-400" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path></svg>
                <a href="diagnosa.php" class="ml-1 text-sm font-medium text-gray-700 hover:text-blue-600 md:ml-2 dark:text-gray-400 dark:hover:text-white">Diagnosa</a>
            </div>
        </li>
        <li>
            <div class="flex items-center">
                <svg aria-hidden="true" class="w-6 h-6 text-gray-400" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path></svg>
                <span class="ml-1 text-sm font-medium text-gray-500 md:ml-2 dark:text-gray-400">Hasil Diagnosa</span>
            </div>
        </li>
    </ol>
</nav>

<section class="bg-white dark:bg-gray-900">
    <div class="py-8 px-4 mx-auto max-w-screen-xl lg:py-16 lg:px-6">
        <div class="w-full p-4 bg-white border rounded-lg shadow-md sm:p-8 dark:bg-gray-800 dark:border-gray-700">
            <h5 class="mb-2 text-3xl font-bold text-gray-900 dark:text-white">Detail Kerusakan</h5>
            <p class="mb-3 font-normal text-gray-700 dark:text-gray-400">Berikut adalah hasil diagnosa dari kerusakan smartphone kamu</p>

            <div class="mb-4">
                <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">ID Kerusakan:</label>
                <input type="text" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:text-white" readonly value="<?= escape($damage['idkerusakan']) ?>">
            </div>

            <div class="mb-4">
                <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Nama Kerusakan:</label>
                <input type="text" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:text-white" readonly value="<?= escape($damage['namakerusakan']) ?>">
            </div>

            <div class="mb-4">
                <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Jenis Smartphone:</label>
                <input type="text" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:text-white" readonly value="<?= escape($damage['jenishp']) ?>">
            </div>

            <div class="mb-4">
                <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Ciri Kerusakan:</label>
                <?php foreach ($symptoms as $symptom): ?>
                <input type="text" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg block w-full p-2.5 mb-2 dark:bg-gray-700 dark:border-gray-600 dark:text-white" readonly value="<?= escape($symptom['gejala']) ?>">
                <?php endforeach; ?>
            </div>

            <div class="mb-4">
                <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Cara Mengatasi:</label>
                <textarea rows="8" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:text-white" readonly><?= escape($damage['caramengatasi'] ?? '') ?></textarea>
            </div>

            <a href="diagnosa.php" class="inline-flex items-center px-3 py-2 text-sm font-medium text-center text-white bg-blue-700 rounded-lg hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                Kembali ke Diagnosa
                <svg aria-hidden="true" class="w-4 h-4 ml-2 -mr-1" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10.293 3.293a1 1 0 011.414 0l6 6a1 1 0 010 1.414l-6 6a1 1 0 01-1.414-1.414L14.586 11H3a1 1 0 110-2h11.586l-4.293-4.293a1 1 0 010-1.414z" clip-rule="evenodd"></path></svg>
            </a>
        </div>
    </div>
</section>

<?php include 'includes/footer.php'; ?>

<script src="https://unpkg.com/flowbite@1.5.4/dist/flowbite.js"></script>
</body>
</html>
