<?php
$user = new User();
$world = new World();
$addresses = new General('addresses');

$countries = $world->getCountries();

$address_list = $addresses->getAll($user->data()->id, 'user_id', '=');
$profile = $user->getProfile();
$item = Input::get('sub1') && is_numeric(Input::get('sub1')) && Input::get('sub') == 'edit' ? $addresses->get(Input::get('sub1')) : null;
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
            <h4 class="mb-0 mx-auto pr-40">Address Book</h4>
        </div>
    </header>

    <div class="container">
        <?php if (Input::get('sub') && Input::get('sub') == 'add' || $item) { ?>
            <div class="row">
                <div class="col-md-12">
                    <form class="form-signin" action="controllers/addresses.php" method="post">
                        <div class="row gy-4">

                            <div class="col-md-4">
                                <label class="form-label" for="country">Country</label>
                                <select name="country" id="country" data-type="country" data-world-target="#profile-state" class="world form-control form-select form-select-lg">
                                    <?php if ($countries) { ?>
                                        <option value="">Select Country</option>
                                        <?php foreach ($countries as $k => $v) { ?>
                                            <option value="<?= $v->id ?>" <?= $item && $item->country == $v->id ? 'selected'  : null; ?>><?= $v->name ?></option>
                                        <?php } ?>
                                    <?php } ?>
                                </select>
                            </div>

                            <div class="col-md-4">
                                <label class="form-label" for="state">State</label>
                                <select name="state" id="profile-state" data-type="state" data-world-target="#profile-city" data-selected="<?= $item ? $item->state : null; ?>" class="world form-control form-select form-select-lg">
                                    <option value="">Select a country</option>
                                    <?php if ($item && $item->country) {
                                        $states = $world->getStatesByCountryId($item->country);
                                    ?>
                                        <?php foreach ($states as $k => $v) { ?>
                                            <option value="<?= $v->id ?>" <?= $item && $item->state == $v->id ? 'selected'  : null; ?>><?= $v->name ?></option>
                                        <?php } ?>
                                    <?php } ?>
                                </select>
                            </div>

                            <div class="col-md-4">
                                <label class="form-label" for="city">City</label>
                                <select name="city" id="profile-city" data-selected="<?= $item ? $item->city : null; ?>" class="city form-control form-select form-select-lg">
                                    <option value="">Select a state</option>
                                    <?php if ($item && $item->state) {
                                        $cities = $world->getCitiesByStateId($item->state);
                                    ?>
                                        <?php foreach ($cities as $k => $v) { ?>
                                            <option value="<?= $v->id ?>" <?= $item && $item->city == $v->id ? 'selected'  : null; ?>><?= $v->name ?></option>
                                        <?php } ?>
                                    <?php } ?>
                                </select>
                            </div>

                            <div class="col-md-12">
                                <label class="form-label" for="title">Title</label>
                                <input name="title" value="<?= $item ? $item->title : null; ?>" id="title" class="form-control form-control-lg" placeholder="Home, Work, Shop, Postal Office, . . ." required>
                            </div>

                            <div class="col-md-12">
                                <label class="form-label" for="address">Address</label>
                                <textarea name="address" id="address" class="form-control form-control-lg" placeholder="Address" placeholder="" style="min-height: 80px;" required><?= $item ? $item->address : null; ?></textarea>
                            </div>

                            <div class="col-12">
                                <input type="hidden" name="rq" value="<?= $item ? 'edit' : 'add'; ?>">
                                <input type="hidden" name="token" value="<?php echo Session::exists('token') ? Session::get('token') : Token::generate(); ?>">
                                <input type="hidden" name="id" value="<?= $item ? $item->id : null; ?>">
                                <button class="btn bg-primary w-100" type="submit">Save</button>
                            </div>
                        </div>


                    </form>
                </div>
            </div>
        <?php } else { ?>
            <div class="row gy-4">
                <div class="col-12">
                    <p class="fs-16p">
                        Below are your delivery addresses you shared with Nescart Eats, you can edit or add another one.</p>
                </div>

                <?php if ($address_list) { ?>
                    <?php foreach ($address_list as $k => $v) { ?>
                        <div class="col-12">
                            <address class="d-flex bg-white rounded shadow p-3">
                                <div class="col-12 flex-fill">
                                    <div class="d-flex justify-content-between">
                                        <h6><?= $v->title; ?></h6>
                                        <?php if($v->title != 'Default'){ ?>
                                            <div class="">
                                                <a href="dashboard/address/edit/<?= $v->id ?>" class="me-3 active"> Edit</a>
                                                <a onclick="return confirm('Are you sure?');" href="controllers/addresses.php?rq=delete&id=<?= $v->id ?>" class=""> <i class="text-danger fa fa-trash"></i> </a>
                                            </div>
                                        <?php } ?>
                                    </div>
                                    <p class="text-truncate mb-0">
                                        <?= $v->address ?>, <?= $world->getCityName($v->city) ?>, <?= $world->getStateName($v->state) ?></p>
                                </div>
                            </address>
                        </div>
                    <?php } ?>
                <?php } ?>

                <div class="col-12">
                    <a href="dashboard/address/add" class="btn bg-primary w-100"> <i class="fa fa-plus"></i> Add new address </a>
                </div>
            </div>

        <?php } ?>
    </div>
</section>