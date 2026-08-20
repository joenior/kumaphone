<?php
/**
 * Shared public navigation bar for user-facing pages.
 *
 * Expects: $activePage (string) — one of 'home', 'kerusakan', 'diagnosa', 'about'
 */
$activePage = $activePage ?? '';

$navItems = [
    ['href' => 'index.html',     'label' => 'Halaman Utama',      'key' => 'home'],
    ['href' => 'kerusakan.php',  'label' => 'Ciri-ciri Kerusakan', 'key' => 'kerusakan'],
    ['href' => 'diagnosa.php',   'label' => 'Diagnosa Kerusakan',  'key' => 'diagnosa'],
    ['href' => 'about.php',      'label' => 'Tentang Kami',        'key' => 'about'],
];
?>
<header>
    <nav class="bg-white border-gray-200 px-4 lg:px-6 py-2.5 dark:bg-gray-800">
        <div class="flex flex-wrap justify-between items-center mx-auto max-w-screen-xl">
            <a href="index.html" class="flex items-center">
                <img src="img/logo.png" class="mr-3 h-6 sm:h-9" alt="Kumaphone Logo">
                <span class="self-center text-xl font-semibold whitespace-nowrap dark:text-white">
                    Kumaphone
                    <span class="bg-gray-100 text-gray-800 text-xs font-semibold mr-2 px-2.5 py-0.5 rounded dark:bg-gray-700 dark:text-gray-300">Beta</span>
                </span>
            </a>
            <div class="flex items-center lg:order-2">
                <a href="login.html" class="text-white bg-primary-700 hover:bg-primary-800 focus:ring-4 focus:ring-primary-300 font-medium rounded-lg text-sm px-4 lg:px-5 py-2 lg:py-2.5 mr-2 dark:bg-primary-600 dark:hover:bg-primary-700 focus:outline-none dark:focus:ring-primary-800">Admin</a>
                <button data-collapse-toggle="mobile-menu-2" type="button" class="inline-flex items-center p-2 ml-1 text-sm text-gray-500 rounded-lg lg:hidden hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-gray-200 dark:text-gray-400 dark:hover:bg-gray-700 dark:focus:ring-gray-600" aria-controls="mobile-menu-2" aria-expanded="false">
                    <span class="sr-only">Open main menu</span>
                    <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M3 5a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zM3 10a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zM3 15a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1z" clip-rule="evenodd"></path></svg>
                    <svg class="hidden w-6 h-6" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path></svg>
                </button>
            </div>
            <div class="hidden justify-between items-center w-full lg:flex lg:w-auto lg:order-1" id="mobile-menu-2">
                <ul class="flex flex-col mt-4 font-medium lg:flex-row lg:space-x-8 lg:mt-0">
                    <?php foreach ($navItems as $item): ?>
                    <li>
                        <a href="<?= $item['href'] ?>"
                           class="block py-2 pr-4 pl-3 <?= $activePage === $item['key']
                               ? 'text-white rounded bg-primary-700 lg:bg-transparent lg:text-primary-700 lg:p-0 dark:text-white'
                               : 'text-gray-700 border-b border-gray-100 hover:bg-gray-50 lg:hover:bg-transparent lg:border-0 lg:hover:text-primary-700 lg:p-0 dark:text-gray-400 lg:dark:hover:text-white dark:hover:bg-gray-700 dark:hover:text-white lg:dark:hover:bg-transparent dark:border-gray-700' ?>"
                           <?= $activePage === $item['key'] ? 'aria-current="page"' : '' ?>>
                            <?= $item['label'] ?>
                        </a>
                    </li>
                    <?php endforeach; ?>
                </ul>
            </div>
        </div>
    </nav>
</header>
