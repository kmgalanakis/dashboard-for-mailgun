var MailgunDashboard_Dashboard = function( $ ) {

    var self = this;

    self.getMailgunLog = function() {
        var data = {
            action:	'mgd_get_mailgun_log',
        };

        $.ajax( {
            url: ajaxurl,
            data: data,
            type: 'POST',
            dataType: 'json',
            success: function( response ) {
                if ( response.success ) {
                    $.each( JSON.parse( response.data ).items, function( index, value ) {
                        var newRow = '<tr>' +
                                        '<td class="status ' + value.type + '" title="' + mailgun_dashboard_dashboard_texts.eventStatus[ value.type ] + '"></td>' +
                                        '<td>' +
                                            value.created_at +
                                        '</td>' +
                                        '<td>' +
                                            value.message +
                                        '</td>' +
                                    '</tr>';
                        $( '#mgd-log-table tbody' ).append( newRow );
                    });

                    $( '#mgd-log-table' ).DataTable(
                        {
                            'iDisplayLength': 25,
                            'order': [[ 1, 'desc' ]],
                            'columnDefs': [
                                {
                                    'targets': [0, 2],
                                    'orderable': false
                                },
                            ],
                            'language' : {
                                'decimal': mailgun_dashboard_dashboard_texts.decimal,
                                'emptyTable': mailgun_dashboard_dashboard_texts.emptyTable,
                                'info': mailgun_dashboard_dashboard_texts.info,
                                'infoEmpty': mailgun_dashboard_dashboard_texts.infoEmpty,
                                'infoFiltered': mailgun_dashboard_dashboard_texts.infoFiltered,
                                'infoPostFix': mailgun_dashboard_dashboard_texts.infoPostFix,
                                'thousands': mailgun_dashboard_dashboard_texts.thousands,
                                'lengthMenu': mailgun_dashboard_dashboard_texts.lengthMenu,
                                'loadingRecords': mailgun_dashboard_dashboard_texts.loadingRecords,
                                'processing': mailgun_dashboard_dashboard_texts.processing,
                                'search': mailgun_dashboard_dashboard_texts.search,
                                'zeroRecords': mailgun_dashboard_dashboard_texts.zeroRecords,
                                'paginate': {
                                    'first': mailgun_dashboard_dashboard_texts.first,
                                    'last': mailgun_dashboard_dashboard_texts.last,
                                    'next': mailgun_dashboard_dashboard_texts.next,
                                    'previous': mailgun_dashboard_dashboard_texts.previous
                                },
                                'aria': {
                                    'sortAscending': mailgun_dashboard_dashboard_texts.sortAscending,
                                    'sortDescending': mailgun_dashboard_dashboard_texts.sortDescending,
                                }
                            }
                        }
                    );
                    $( '.mgd-loading-section' ).fadeOut();
                    $( '#mgd-log-table-container' ).fadeIn();
                } else {
                    alert( response.data );
                }
            }
        });
    }

    self.drawChart = function() {
        var ctx = document.getElementById("myChart");
        var myChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: ["Red", "Blue", "Yellow", "Green", "Purple", "Orange"],
                datasets: [{
                    label: '# of Votes',
                    data: [12, 19, 3, 5, 2, 3],
                    backgroundColor: [
                        'rgba(255, 99, 132, 0.2)',
                        'rgba(54, 162, 235, 0.2)',
                        'rgba(255, 206, 86, 0.2)',
                        'rgba(75, 192, 192, 0.2)',
                        'rgba(153, 102, 255, 0.2)',
                        'rgba(255, 159, 64, 0.2)'
                    ],
                    borderColor: [
                        'rgba(255,99,132,1)',
                        'rgba(54, 162, 235, 1)',
                        'rgba(255, 206, 86, 1)',
                        'rgba(75, 192, 192, 1)',
                        'rgba(153, 102, 255, 1)',
                        'rgba(255, 159, 64, 1)'
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                scales: {
                    yAxes: [{
                        ticks: {
                            beginAtZero:true
                        }
                    }]
                }
            }
        });
    }

    self.init = function() {
        self.getMailgunLog();
        // self.drawChart();
    };

    self.init();

}

jQuery( document ).ready( function( $ ) {
    var dashboardInstance = new MailgunDashboard_Dashboard( $ );
});