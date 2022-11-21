<?php
include_once('core/init.php');
$user = isset($user) ? $user : new User();
!$user->isLoggedIn() ? Redirect::to('login') : null;
$categories = new General('categories');
$menus = new Menus();

$keyword = Input::get('keyword') ? Input::get('keyword') : null;
$searchTerm = $keyword ? "WHERE title LIKE '%{$keyword}%' OR description LIKE '%{$keyword}%' AND status = 1" : null;

$page = "dashboard/search?keyword={$keyword}";
$current = Input::get('p') ? Input::get('p') : 1;
$per_page = 40;
$pagination = new Pagination();
$total_record = $pagination->countAll('menus', $searchTerm);
$paginate = new Pagination($current, $per_page, $total_record);
$menu_list = $searchTerm ? $menus->getPages($per_page, $paginate->offset(), $searchTerm, 'ORDER BY date_added DESC') : null;


Alerts::displayError();
Alerts::displaySuccess();
?>

<section class="container-fluid py-5">
    <header class="container mb-6">
        <div class="d-flex align-items-center justify-contnt-between">
            <!-- Back -->
            <a href="#" id="back_button" class="d-flex p-2 border-0 rounded shadow bg-white">
                <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 22.5 38.381">
                    <path id="chevron-left" d="M30.949,7.767a3.526,3.526,0,0,1,0,5.216l-12.4,12.932,12.4,12.932a3.516,3.516,0,0,1,0,5.207,3.93,3.93,0,0,1-5.344,0c-1.384-1.344-14.9-15.535-14.9-15.535a3.619,3.619,0,0,1,0-5.214S24.221,9.119,25.6,7.769a3.928,3.928,0,0,1,5.344,0Z" transform="translate(-9.6 -6.72)" />
                </svg>

            </a>
            <h4 class="mb-0 mx-auto pr-40">Search</h4>
        </div>
    </header>

    <!-- Search Meals -->
    <section class="container-fluid mb-5">
        <div class="container">
            <?php if ($menu_list) {
                Component::render(
                    'menu',
                    array(
                        'data' => $menu_list,
                        'type' => 'list',
                        'title' => null
                    )
                );

                Component::render(
                    'pagination',
                    array(
                        'paginate' => $paginate,
                        'per_page' => $per_page,
                        'page' => $page,
                        'current' => $current,
                        'total' => $total_record,
                    )
                );
            } else { ?>

                <div class="row">
                    <div class="col-lg-12">
                        <p class="text-center fs-18p">No result found for your search!</p>
                    </div>
                </div>
            <?php } ?>
        </div>
    </section>
</section>