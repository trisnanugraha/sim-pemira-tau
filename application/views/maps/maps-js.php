<script type="text/javascript">
    var test = new Array();

    $(document).ready(function() {
        //Ajax Load data from ajax
        $.ajax({
            url: "<?php echo site_url('maps/fetch_data') ?>",
            type: "GET",
            dataType: "JSON",
            success: function(data) {

                var mymap = L.map('mapid').setView([data[0].longitude, data[0].latitude], 15);

                L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                    attribution: '© OpenStreetMap contributors',
                    maxZoom: 15,
                }).addTo(mymap);

                // console.log((data[0].lokasi))
                for (var i = 0; i < data.length; i++) {

                    var html = "<table class=\"\">\
                                    <tr>\
                                        <td>Lokasi</td>\
                                        <td class=\"px-2\">:</td>\
                                        <td>" + data[i].lokasi + "</td>\
                                    </tr>\
                                    <tr>\
                                        <td>Longitude</td>\
                                        <td class=\"px-2\">:</td>\
                                        <td>" + data[i].longitude + "</td>\
                                    </tr>\
                                    <tr>\
                                        <td>Latitude</td>\
                                        <td class=\"px-2\">:</td>\
                                        <td>" + data[i].latitude + "</td>\
                                    </tr>\
                                    <tr>\
                                        <td>Tanggal Pemupukan</td>\
                                        <td class=\"px-2\">:</td>\
                                        <td>" + data[i].tgl_pemupukan + "</td>\
                                    </tr>\
                                    <tr>\
                                        <td>Tanggal Tanam</td>\
                                        <td class=\"px-2\">:</td>\
                                        <td>" + data[i].tgl_tanam + "</td>\
                                    </tr>\
                                    <tr>\
                                        <td>Jumlah Tanam</td>\
                                        <td class=\"px-2\">:</td>\
                                        <td>" + data[i].jml_tanam + "</td>\
                                    </tr>\
                                    <tr>\
                                        <td>Jumlah Panen</td>\
                                        <td class=\"px-2\">:</td>\
                                        <td>" + data[i].jumlah_panen + "</td>\
                                    </tr>\
                                </table>"

                    marker = new L.marker([data[i].longitude, data[i].latitude])
                        .bindPopup(html)
                        .addTo(mymap);
                }
                
                mymap.fitBounds(locations, {
                    padding: [50, 50]
                });
            },
            error: function(jqXHR, textStatus, errorThrown) {
                alert('Error get data from ajax');
            }
        });

    });
</script>