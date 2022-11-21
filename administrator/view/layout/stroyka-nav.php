<?php
$pagination = new Pagination();

$pending_count = $pagination->countAll('orders', "WHERE id > 0 AND status = 1");
$pending_payout = $pagination->countAll('wallets', "WHERE id > 0 AND payout_balance > 0");
$unread_notification_count = $pagination->countAll('notifications', "WHERE id > 0 AND user_id = 0 AND status = 0");
?>

<!-- sa-app -->
<div class="sa-app sa-app--desktop-sidebar-shown sa-app--mobile-sidebar-hidden sa-app--toolbar-fixed">
    <!-- sa-app__sidebar -->
    <div class="sa-app__sidebar">
        <div class="sa-sidebar">
            <div class="sa-sidebar__header"><a class="sa-sidebar__logo" href="dashboard">
                    <!-- logo -->
                    <div class="sa-sidebar-logo">
                        <h5 class="fw-bold text-white"><?= SITE_NAME; ?></h5>
                        <div class="sa-sidebar-logo__caption">Admin</div>
                    </div><!-- logo / end -->
                </a></div>
            <div class="sa-sidebar__body" data-simplebar="">
                <ul class="sa-nav sa-nav--sidebar" data-sa-collapse="">
                    <li class="sa-nav__section">
                        <div class="sa-nav__section-title"><span>Application</span></div>
                        <ul class="sa-nav__menu sa-nav__menu--root">
                            <li class="sa-nav__menu-item sa-nav__menu-item--has-icon">
                                <a href="dashboard" class="sa-nav__link">
                                    <img src="<?= SITE_URL ?>assets/icons/Dashboard.svg" class="icon-menu svg-inline--fa text-white me-4">
                                    <span class="sa-nav__title">Dashboard</span>
                                </a>
                            </li>
                            <li class="sa-nav__menu-item sa-nav__menu-item--has-icon">
                                <a href="orders" class="sa-nav__link">
                                    <img src="<?= SITE_URL ?>assets/icons/My Orders.svg" class="icon-menu svg-inline--fa text-white me-4">
                                    <span class="sa-nav__title">Orders</span> <span class="sa-nav__menu-item-badge badge badge-sa-pill badge-sa-theme text-white"><?= $pending_count ?></span></a>
                            </li>

                            <li class="sa-nav__menu-item sa-nav__menu-item--has-icon">
                                <a href="payouts" class="sa-nav__link">
                                    <img src="<?= SITE_URL ?>assets/icons/wallet.svg" class="icon-menu svg-inline--fa text-white me-4">
                                    <span class="sa-nav__title">Payouts</span> <span class="sa-nav__menu-item-badge badge badge-sa-pill badge-sa-theme text-white"><?= $pending_payout ?></span></a>
                            </li>
                        </ul>
                    </li>
                    <li class="sa-nav__section">
                        <div class="sa-nav__section-title"><span>Catelog</span></div>
                        <ul class="sa-nav__menu sa-nav__menu--root">
                            <li class="sa-nav__menu-item">
                                <a href="menus" class="sa-nav__link">
                                    <img src="<?= SITE_URL ?>assets/icons/Products.svg" class="icon-menu svg-inline--fa text-white me-4">
                                    <span class="sa-nav__title">Menus</span></a>
                            </li>

                            <li class="sa-nav__menu-item"><a href="categories" class="sa-nav__link">
                                    <img src="<?= SITE_URL ?>assets/icons/Dashboard.svg" class="icon-menu svg-inline--fa text-white me-4">
                                    <span class="sa-nav__title">Categories</span></a></li>

                            <li class="sa-nav__menu-item"><a href="specialcats" class="sa-nav__link">
                                    <img src="<?= SITE_URL ?>assets/icons/Dashboard.svg" class="icon-menu svg-inline--fa text-white me-4">
                                    </span><span class="sa-nav__title">Special Categories</span></a></li>
                        </ul>
                    </li>
                    <li class="sa-nav__section">
                        <div class="sa-nav__section-title"><span>Customers</span></div>
                        <ul class="sa-nav__menu sa-nav__menu--root">
                            <li class="sa-nav__menu-item"><a href="buyers" class="sa-nav__link"><img src="<?= SITE_URL ?>assets/icons/Dashboard.svg" class="icon-menu svg-inline--fa text-white me-4"><span class="sa-nav__title">Buyers</span></a></li>
                            <li class="sa-nav__menu-item"><a href="vendors" class="sa-nav__link"><img src="<?= SITE_URL ?>assets/icons/Dashboard.svg" class="icon-menu svg-inline--fa text-white me-4"><span class="sa-nav__title">Vendors</span></a></li>
                        </ul>
                    </li>
                    <li class="sa-nav__section">
                        <div class="sa-nav__section-title"><span>Messaging</span></div>
                        <ul class="sa-nav__menu sa-nav__menu--root">
                            <li class="sa-nav__menu-item">
                                <a href="messagings" class="sa-nav__link">
                                    <img src="<?= SITE_URL ?>assets/icons/Messages.svg" class="icon-menu svg-inline--fa text-white me-4">
                                    <span class="sa-nav__title">Send Message</span></a>
                            </li>

                            <li class="sa-nav__menu-item sa-nav__menu-item--has-icon" data-sa-collapse-item="sa-nav__menu-item--open">
                                <a href="#" class="sa-nav__link" data-sa-collapse-trigger="">
                                    <img src="<?= SITE_URL ?>assets/icons/Messages.svg" class="icon-menu svg-inline--fa text-white me-4">
                                    <span class="sa-nav__title">Messages</span><span class="sa-nav__arrow"><svg xmlns="http://www.w3.org/2000/svg" width="6" height="9" viewBox="0 0 6 9" fill="currentColor">
                                            <path d="M5.605,0.213 C6.007,0.613 6.107,1.212 5.706,1.612 L2.696,4.511 L5.706,7.409 C6.107,7.809 6.107,8.509 5.605,8.808 C5.204,9.108 4.702,9.108 4.301,8.709 L-0.013,4.511 L4.401,0.313 C4.702,-0.087 5.304,-0.087 5.605,0.213 Z">
                                            </path>
                                        </svg></span></a>
                                <ul class="sa-nav__menu sa-nav__menu--sub" data-sa-collapse-content="">
                                    <li class="sa-nav__menu-item"><a href="messagings" class="sa-nav__link"><img src="<?= SITE_URL ?>assets/icons/Messages.svg" class="icon-menu svg-inline--fa text-white me-4"><span class="sa-nav__title">Sent Messages</a></li>
                                    <li class="sa-nav__menu-item"><a href="message-snippets" class="sa-nav__link"><img src="<?= SITE_URL ?>assets/icons/Messages.svg" class="icon-menu svg-inline--fa text-white me-4"><span class="sa-nav__title">Snippets</span></a></li>
                                </ul>
                            </li>

                            <li class="sa-nav__menu-item sa-nav__menu-item--has-icon" data-sa-collapse-item="sa-nav__menu-item--open">
                                <a href="#" class="sa-nav__link" data-sa-collapse-trigger="">
                                    <img src="<?= SITE_URL ?>assets/icons/Notifications.svg" class="icon-menu svg-inline--fa text-white me-4">
                                    <span class="sa-nav__title">Notifications</span><span class="sa-nav__arrow"><svg xmlns="http://www.w3.org/2000/svg" width="6" height="9" viewBox="0 0 6 9" fill="currentColor">
                                            <path d="M5.605,0.213 C6.007,0.613 6.107,1.212 5.706,1.612 L2.696,4.511 L5.706,7.409 C6.107,7.809 6.107,8.509 5.605,8.808 C5.204,9.108 4.702,9.108 4.301,8.709 L-0.013,4.511 L4.401,0.313 C4.702,-0.087 5.304,-0.087 5.605,0.213 Z">
                                            </path>
                                        </svg></span></a>
                                <ul class="sa-nav__menu sa-nav__menu--sub" data-sa-collapse-content="">
                                    <li class="sa-nav__menu-item"><a href="notifications" class="sa-nav__link"><img src="<?= SITE_URL ?>assets/icons/Notifications.svg" class="icon-menu svg-inline--fa text-white me-4"><span class="sa-nav__title">List</span> <span class="sa-nav__menu-item-badge badge badge-sa-pill badge-sa-theme text-white"><?= $unread_notification_count ?></span></a></li>
                                    <li class="sa-nav__menu-item"><a href="notification-snippets" class="sa-nav__link"><img src="<?= SITE_URL ?>assets/icons/Notifications.svg" class="icon-menu svg-inline--fa text-white me-4"><span class="sa-nav__title">Snippets</span></a></li>
                                </ul>
                            </li>
                            <li class="sa-nav__menu-item sa-nav__menu-item--has-icon"><a href="sms-snippets" class="sa-nav__link"><span class="sa-nav__icon"><i class="fas fa-file-alt"></i></span><span class="sa-nav__title">SMS</span></a></li>
                        </ul>
                    </li>
                    <li class="sa-nav__section">
                        <div class="sa-nav__section-title"><span>Settings</span></div>
                        <ul class="sa-nav__menu sa-nav__menu--root">
                            <li class="sa-nav__menu-item"><a href="general" class="sa-nav__link"><img src="<?= SITE_URL ?>assets/icons/Dashboard.svg" class="icon-menu svg-inline--fa text-white me-4"><span class="sa-nav__title">General</span></a></li>
                            <li class="sa-nav__menu-item"><a href="administrators" class="sa-nav__link"><img src="<?= SITE_URL ?>assets/icons/Dashboard.svg" class="icon-menu svg-inline--fa text-white me-4"><span class="sa-nav__title">Administrators</span></a></li>
                            <li class="sa-nav__menu-item"><a href="delivery-fees" class="sa-nav__link"><img src="<?= SITE_URL ?>assets/icons/Dashboard.svg" class="icon-menu svg-inline--fa text-white me-4"><span class="sa-nav__title">Delivery Fees</span></a></li>
                            <li class="sa-nav__menu-item"><a href="coupons" class="sa-nav__link"><img src="<?= SITE_URL ?>assets/icons/Dashboard.svg" class="icon-menu svg-inline--fa text-white me-4"><span class="sa-nav__title">Coupons</span></a></li>
                            <li class="sa-nav__menu-item"><a href="ads" class="sa-nav__link"><img src="<?= SITE_URL ?>assets/icons/Dashboard.svg" class="icon-menu svg-inline--fa text-white me-4"><span class="sa-nav__title">Adverts</span></a></li>
                            <li class="sa-nav__menu-item"><a href="slideshows" class="sa-nav__link"><img src="<?= SITE_URL ?>assets/icons/Dashboard.svg" class="icon-menu svg-inline--fa text-white me-4"><span class="sa-nav__title">Slideshows</span></a></li>
                            <li class="sa-nav__menu-item"><a href="settings" class="sa-nav__link"><img src="<?= SITE_URL ?>assets/icons/Dashboard.svg" class="icon-menu svg-inline--fa text-white me-4"><span class="sa-nav__title">Change Password</span></a></li>
                        </ul>
                    </li>
                    <li class="sa-nav__section">
                        <div class="sa-nav__section-title"><span>Pages</span></div>
                        <ul class="sa-nav__menu sa-nav__menu--root">
                            <li class="sa-nav__menu-item sa-nav__menu-item--has-icon"><a href="blog" class="sa-nav__link"><span class="sa-nav__icon"><i class="fas fa-book-open"></i></span><span class="sa-nav__title">Blog</span></a></li>
                            <li class="sa-nav__menu-item sa-nav__menu-item--has-icon"><a href="pages" class="sa-nav__link"><span class="sa-nav__icon"><i class="fas fa-book-open"></i></span><span class="sa-nav__title">Pages</span></a></li>
                            <li class="sa-nav__menu-item sa-nav__menu-item--has-icon"><a href="faqs" class="sa-nav__link"><span class="sa-nav__icon"><i class="fas fa-question-circle"></i></span><span class="sa-nav__title">FAQs</span></a></li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
        <div class="sa-app__sidebar-shadow"></div>
        <div class="sa-app__sidebar-backdrop" data-sa-close-sidebar=""></div>
    </div><!-- sa-app__sidebar / end -->

    <!-- sa-app__content -->
    <div class="sa-app__content">
        <!-- sa-app__toolbar -->
        <div class="sa-toolbar sa-toolbar--search-hidden sa-app__toolbar">
            <div class="sa-toolbar__body">
                <div class="sa-toolbar__item"><button class="sa-toolbar__button" type="button" aria-label="Menu" data-sa-toggle-sidebar=""><svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20" fill="currentColor">
                            <path d="M1,11V9h18v2H1z M1,3h18v2H1V3z M15,17H1v-2h14V17z"></path>
                        </svg></button></div>
                <div class="sa-toolbar__item sa-toolbar__item--search">
                    <form class="sa-search sa-search--state--pending">
                        <div class="sa-search__body"><label class="visually-hidden" for="input-search">Search
                                for:</label>
                            <div class="sa-search__icon"><svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 16 16" fill="currentColor">
                                    <path d="M16.243 14.828C16.243 14.828 16.047 15.308 15.701 15.654C15.34 16.015 14.828 16.242 14.828 16.242L10.321 11.736C9.247 12.522 7.933 13 6.5 13C2.91 13 0 10.09 0 6.5C0 2.91 2.91 0 6.5 0C10.09 0 13 2.91 13 6.5C13 7.933 12.522 9.247 11.736 10.321L16.243 14.828ZM6.5 2C4.015 2 2 4.015 2 6.5C2 8.985 4.015 11 6.5 11C8.985 11 11 8.985 11 6.5C11 4.015 8.985 2 6.5 2Z">
                                    </path>
                                </svg></div><input type="text" id="input-search" class="sa-search__input" placeholder="Search for the truth" autoComplete="off" /><button class="sa-search__cancel d-sm-none" type="button" aria-label="Close search"><svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 12 12" fill="currentColor">
                                    <path d="M10.8,10.8L10.8,10.8c-0.4,0.4-1,0.4-1.4,0L6,7.4l-3.4,3.4c-0.4,0.4-1,0.4-1.4,0l0,0c-0.4-0.4-0.4-1,0-1.4L4.6,6L1.2,2.6 c-0.4-0.4-0.4-1,0-1.4l0,0c0.4-0.4,1-0.4,1.4,0L6,4.6l3.4-3.4c0.4-0.4,1-0.4,1.4,0l0,0c0.4,0.4,0.4,1,0,1.4L7.4,6l3.4,3.4 C11.2,9.8,11.2,10.4,10.8,10.8z">
                                    </path>
                                </svg></button>
                            <div class="sa-search__field"></div>
                        </div>
                        <div class="sa-search__dropdown">
                            <div class="sa-search__dropdown-loader"></div>
                            <div class="sa-search__dropdown-wrapper">
                                <div class="sa-search__suggestions sa-suggestions"></div>
                                <div class="sa-search__help sa-search__help--type--no-results">
                                    <div class="sa-search__help-title">No results for &quot;<span class="sa-search__query"></span>&quot;</div>
                                    <div class="sa-search__help-subtitle">Make sure that all words are spelled
                                        correctly.</div>
                                </div>
                                <div class="sa-search__help sa-search__help--type--greeting">
                                    <div class="sa-search__help-title">Start typing to search for</div>
                                    <div class="sa-search__help-subtitle">Products, orders, customers, actions, etc.
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="sa-search__backdrop"></div>
                    </form>
                </div>
                <div class="mx-auto"></div>
                <div class="sa-toolbar__item d-sm-none"><button class="sa-toolbar__button" type="button" aria-label="Show search" data-sa-action="show-search"><svg xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 16 16" fill="currentColor">
                            <path d="M16.243 14.828C16.243 14.828 16.047 15.308 15.701 15.654C15.34 16.015 14.828 16.242 14.828 16.242L10.321 11.736C9.247 12.522 7.933 13 6.5 13C2.91 13 0 10.09 0 6.5C0 2.91 2.91 0 6.5 0C10.09 0 13 2.91 13 6.5C13 7.933 12.522 9.247 11.736 10.321L16.243 14.828ZM6.5 2C4.015 2 2 4.015 2 6.5C2 8.985 4.015 11 6.5 11C8.985 11 11 8.985 11 6.5C11 4.015 8.985 2 6.5 2Z">
                            </path>
                        </svg></button></div>


                <div class="dropdown sa-toolbar__item">
                    <button class="sa-toolbar-user" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown" data-bs-offset="0,1" aria-expanded="false">
                        <span class="sa-toolbar-user__avatar sa-symbol sa-symbol--shape--rounded">
                            <img src="assets/img/user-avatar.jpg" width="64" height="64" alt="" />
                        </span>
                        <span class="sa-toolbar-user__info"><span class="sa-toolbar-user__title"><?= $user->data()->first_name . ' ' . $user->data()->last_name; ?></span>
                            <span class="sa-toolbar-user__subtitle"><?= $user->data()->email; ?></span>
                        </span>
                    </button>
                    <ul class="dropdown-menu w-100" aria-labelledby="dropdownMenuButton">
                        <li><a class="dropdown-item" href="settings">Settings</a></li>
                        <li>
                            <hr class="dropdown-divider" />
                        </li>
                        <li><a class="dropdown-item" href="controllers/logout.php">Sign Out</a></li>
                    </ul>
                </div>
            </div>
            <div class="sa-toolbar__shadow"></div>
        </div><!-- sa-app__toolbar / end -->