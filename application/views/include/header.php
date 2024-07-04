<?php
$list_region_user = $this->session->userdata('list_region');
$foto = $this->session->userdata('foto');
$nama_user = $this->session->userdata('ap_nama');
$jabatan = $this->session->userdata('jabatan');
?>
<style>
    .header-logo {
        font-size: 36px;
        float: left;
    }
</style>
<header class="pc-header">
    <div class="m-header">

        <?php if (count($list_region_user) > 1) : ?>
            <div class="header-logo">
                <img src="<?= base_url() . 'assets/logo.jpg' ?>" alt="" class="logo logo-lg" style="width: 40px;" />
            </div>

            <h4 style="padding-top: 10px;">Telemetry View</h4>
        <?php else : ?>
            <div class="header-logo">
                <img src="<?= base_url() . 'assets/upload/' . $list_region_user[0]->logo_site ?>" alt="" class="logo logo-lg" style="width: 40px;" />
                <!-- <img src="<?= base_url() . 'assets/logo_simetri.png' ?>" alt="" class="logo logo-lg" style="width: 40px;" /> -->
            </div>

            <h4 style="padding-top: 10px;"><?= $list_region_user[0]->site_name ?></h4>
        <?php endif; ?>

        <h4 style="padding-top: 10px;"></h4>
        <div class="pc-h-item">
            <a href="#" class="pc-head-link head-link-secondary m-0" id="sidebar-hide">
                <i class="ti ti-menu-2"></i>
            </a>
        </div>
    </div>
    <div class="header-wrapper">
        <div class="me-auto pc-mob-drp">
            <ul class="list-unstyled">
                <li class="pc-h-item header-mobile-collapse">
                    <a href="#" class="pc-head-link head-link-secondary ms-0" id="mobile-collapse">
                        <i class="ti ti-menu-2"></i>
                    </a>
                </li>
            </ul>
        </div>
        <div class="ms-auto justify-content-center">
            <a href="#" class="bg-light rounded p-1 fs-3 ">
                <i class="ti ti-clock"></i>
                <span id="local-day"></span>,
                <span id="local-date"></span> -
                <span id="local-time"></span>
            </a>
            <script>
                function updateLocalTime() {
                    var now = new Date();
                    var localTime = now.toLocaleTimeString();
                    var optionsDate = {
                        day: '2-digit',
                        month: 'short',
                        year: 'numeric'
                    };
                    var optionsDay = {
                        weekday: 'long'
                    };
                    var localDate = now.toLocaleDateString('id-ID', optionsDate);
                    var localDay = now.toLocaleDateString('id-ID', optionsDay);
                    document.getElementById('local-time').textContent = localTime;
                    document.getElementById('local-date').textContent = localDate;
                    document.getElementById('local-day').textContent = localDay;
                }
                setInterval(updateLocalTime, 1000);
                updateLocalTime();
            </script>
            <ul class="list-unstyled">

                <li class="dropdown pc-h-item header-user-profile">
                    <a class="pc-head-link head-link-primary dropdown-toggle arrow-none me-0" data-bs-toggle="dropdown" href="#" role="button" aria-haspopup="false" aria-expanded="false">
                        <img src="<?= base_url() ?>assets/upload/<?= $foto ?>" class="user-avtar" />
                        <span>
                            <i class="ti ti-settings"></i>
                        </span>
                    </a>
                    <div class="dropdown-menu dropdown-user-profile dropdown-menu-end pc-h-dropdown">
                        <div class="dropdown-header">
                            <h4>Welcome, <span class="small text-muted"> <?= $nama_user ?></span>
                            </h4>
                            <p class="text-muted"><?= $jabatan ?></p>
                            <hr />
                            <div class="profile-notification-scroll position-relative" style="max-height: calc(100vh - 280px)">

                                <a href="<?= base_url('Secure/logout') ?>" class="dropdown-item">
                                    <i class="ti ti-logout"></i>
                                    <span>Logout</span>
                                </a>
                            </div>
                        </div>
                    </div>
                </li>
            </ul>
        </div>
    </div>
</header>