<!DOCTYPE html>
<html lang="en">
	<!-- Mirrored from berrydashboard.io/bootstrap/default/widget/w_chart.html by HTTrack Website Copier/3.x [XR&CO'2014], Tue, 20 Dec 2022 01:41:46 GMT -->
	<head>
		<title>Chart Widget | Berry Bootstrap 5 Admin Template</title>
		<meta charset="utf-8" />
		<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui" />
		<meta http-equiv="X-UA-Compatible" content="IE=edge" />
		<meta name="description" content="Berry is made using Bootstrap 5 design framework. Download the free admin template & use it for your project." />
		<meta name="keywords" content="Berry, Dashboard UI Kit, Bootstrap 5, Admin Template, Admin Dashboard, CRM, CMS, Bootstrap Admin Template" />
		<meta name="author" content="CodedThemes" />
		<link rel="icon" href="https://berrydashboard.io/bootstrap/default/assets/images/favicon.svg" type="image/x-icon" />
		<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&amp;display=swap" id="main-font-link" />
		<link rel="stylesheet" href="<?= base_url() ?>assets/fonts/tabler-icons.min.css" />
		<link rel="stylesheet" href="<?= base_url() ?>assets/fonts/feather.css" />
		<link rel="stylesheet" href="<?= base_url() ?>assets/fonts/fontawesome.css" />
		<link rel="stylesheet" href="<?= base_url() ?>assets/fonts/material.css" />
		<link rel="stylesheet" href="<?= base_url() ?>assets/css/style.css" id="main-style-link" />
		<link rel="stylesheet" href="<?= base_url() ?>assets/css/style-preset.css" id="preset-style-link" />
	</head>
	<body>
		<div class="loader-bg">
			<div class="loader-track">
				<div class="loader-fill"></div>
			</div>
		</div>
		<?php $this->load->view('include/header.php'); ?>
		<?php $this->load->view('include/sidebar.php'); ?>
		<div class="pc-container">
			<div class="pc-content">
				<div class="page-header">
					<div class="page-block">
						<div class="row align-items-center">
							<div class="col-md-12">
								<div class="page-header-title">
									<h5 class="m-b-10">Chart</h5>
								</div>
								<ul class="breadcrumb">
									<li class="breadcrumb-item">
										<a href="https://berrydashboard.io/bootstrap/default/navigation/index.html">Home</a>
									</li>
									<li class="breadcrumb-item">
										<a href="javascript: void(0)">Widget</a>
									</li>
									<li class="breadcrumb-item" aria-current="page">Chart</li>
								</ul>
							</div>
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-xl-12">
						<div class="card">
							<div class="card-body">
                                <div class="row">
                                    <div class="form-group col-md-2">
                                        <label class="form-label" for="ms_regions_id">Site:</label>
                                        <select class="form-control" name="ms_regions_id" id="ms_regions_id">
                                            <option>--- Pilih Site ---</option>
                                            <?php foreach ($region as $reg) { ?>
                                                <option value="<?= $reg->id ?>"><?= $reg->site_name ?></option>
                                            <?php } ?>
                                        </select>
                                    </div>

                                    <div class="form-group col-md-2">
                                        <label class="form-label" for="stasiun">Stasiun</label>
                                        <select class="form-control" name="stasiun" id="stasiun">
                                            <option>--Pilih Stasiun--</option>
                                        </select>
                                    </div>
                                </div>
								
							</div>
							<div id="account-chart"></div>
						</div>
					</div>
					
				</div>
			</div>
		</div>
		<footer class="pc-footer">
			<div class="footer-wrapper container-fluid">
				<div class="row">
					<div class="col my-1">
						<p class="m-0">Copyright &copy; <a href="#">SPIB</a>
						</p>
					</div>
				</div>
			</div>
		</footer>
		<script src="<?= base_url() ?>assets/js/plugins/popper.min.js"></script>
		<script src="<?= base_url() ?>assets/js/plugins/simplebar.min.js"></script>
		<script src="<?= base_url() ?>assets/js/plugins/bootstrap.min.js"></script>
		<script src="<?= base_url() ?>assets/js/config.js"></script>
		<script src="<?= base_url() ?>assets/js/pcoded.js"></script>
		<script src="<?= base_url() ?>assets/js/plugins/feather.min.js"></script>
		<script src="<?= base_url() ?>assets/js/customizer.js"></script>
		<script src="<?= base_url() ?>assets/js/plugins/apexcharts.min.js"></script>
        <script src="<?= base_url() ?>assets/js/jquery-3.1.1.min.js"></script>
        <script>
            $("#ms_regions_id").change(function() {
                var ms_regions_id = $(this).val();

                var xhttp = new XMLHttpRequest();
                xhttp.onreadystatechange = function() {
                    if (this.readyState == 4 && this.status == 200) {
                        var result = JSON.parse(xhttp.responseText).result;
                        var html = "<option>--- Pilih Stasiun ---</option>";
                        var stasiun = document.getElementById('stasiun');

                        if (result.length > 0) {
                            for (i = 0; i < result.length; i++) {
                                html += '<option value=' + result[i].id + '>' + result[i].nama_stasiun +
                                    '</option>';
                            }
                        } else {
                            html = '<option>--Tidak Ada Data--</option>';
                        }

                        stasiun.innerHTML = html;
                    }
                };
                xhttp.open("GET", "<?php echo base_url('Data/stasiun'); ?>" + '?ms_regions_id=' + ms_regions_id,
                    true);
                xhttp.send();
            });

            (function() {
                var options1 = {
                    chart: {
                        type: 'area',
                        height: 215,
                        sparkline: {
                            enabled: true
                        }
                    },
                    colors: ['#673ab7', '#2196f3', '#f44336'],
                    stroke: {
                        curve: 'smooth',
                        width: 2
                    },
                    fill: {
                        type: 'gradient',
                        gradient: {
                            shadeIntensity: 1,
                            opacityFrom: 0.5,
                            opacityTo: 0,
                            stops: [0, 80, 100]
                        }
                    },
                    series: [{
                        name: 'Youtube',
                        data: [10, 90, 65, 85, 40, 80, 30]
                    }, {
                        name: 'Facebook',
                        data: [50, 30, 25, 15, 60, 10, 25]
                    }, {
                        name: 'Twitter',
                        data: [5, 50, 40, 55, 20, 40, 20]
                    }],
                    tooltip: {
                        fixed: {
                            enabled: false
                        },
                        x: {
                            show: false
                        },
                        y: {
                            title: {
                                formatter: function(seriesName) {
                                    return '';
                                }
                            }
                        },
                        marker: {
                            show: false
                        }
                    }
                };
                new ApexCharts(document.querySelector('#account-chart'), options1).render();
            })();

            $("#stasiun").change(function() {
                
                for (var a=[],i=0;i<10;++i) a[i]=i;
                for (var b=[],i=0;i<10;++i) b[i]=i;
                for (var c=[],i=0;i<10;++i) c[i]=i;

                Youtube = shuffle(a);
                Facebook = shuffle(b);
                Twitter = shuffle(c);

                var options1 = {
                    chart: {
                        type: 'area',
                        height: 215,
                        sparkline: {
                            enabled: true
                        }
                    },
                    colors: ['#673ab7', '#2196f3', '#f44336'],
                    stroke: {
                        curve: 'smooth',
                        width: 2
                    },
                    fill: {
                        type: 'gradient',
                        gradient: {
                            shadeIntensity: 1,
                            opacityFrom: 0.5,
                            opacityTo: 0,
                            stops: [0, 80, 100]
                        }
                    },
                    series: [{
                        name: 'Youtube',
                        data: Youtube
                    }, {
                        name: 'Facebook',
                        data: Facebook
                    }, {
                        name: 'Twitter',
                        data: Twitter
                    }],
                    tooltip: {
                        fixed: {
                            enabled: false
                        },
                        x: {
                            show: false
                        },
                        y: {
                            title: {
                                formatter: function(seriesName) {
                                    return '';
                                }
                            }
                        },
                        marker: {
                            show: false
                        }
                    }
                };
                new ApexCharts(document.querySelector('#account-chart'), options1).render();
            });
            
            function shuffle(array) {
                var tmp, current, top = array.length;
                if(top) while(--top) {
                    current = Math.floor(Math.random() * (top + 1));
                    tmp = array[current];
                    array[current] = array[top];
                    array[top] = tmp;
                }
                return array;
            };

            
        </script>
	</body>
	<!-- Mirrored from berrydashboard.io/bootstrap/default/widget/w_chart.html by HTTrack Website Copier/3.x [XR&CO'2014], Tue, 20 Dec 2022 01:41:47 GMT -->
</html>