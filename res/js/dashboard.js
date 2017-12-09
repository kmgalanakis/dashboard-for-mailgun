var MailgunDashboard_Dashboard = function( $ ) {

    var self = this;

    self.mgd_dashboard_action = 'mgd_get_mailgun_dashboard_api';

    self.getMailgunEvents = function() {
        var data = {
            action:	self.mgd_dashboard_action,
            type: 'events'
        };

        $.ajax( {
            url: ajaxurl,
            data: data,
            type: 'POST',
            dataType: 'json',
            success: function( response ) {
                if ( response.success ) {
                    // console.log( JSON.parse( response.data ) );
                    $( '#mgd-log-events-chart' ).prev( '.mgd-loading-section' ).fadeOut();
                    $( '#mgd-log-events-chart' ).fadeIn();
                } else {
                    alert( response.data );
                }
            }
        });
    }

    self.getMailgunLog = function() {
        var data = {
            action:	self.mgd_dashboard_action,
            type: 'log'
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
                                    'orderable': false,
                                },
                                {
                                    'type': 'date',
                                    'targets': [1],
                                },
                            ],
                            bAutoWidth: false ,
                            aoColumns : [
                                { sWidth: '1%' },
                                { sWidth: '15%' },
                                { sWidth: '73%' },
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
                    $( '#mgd-log-table-container' ).prev( '.mgd-loading-section' ).fadeOut();
                    $( '#mgd-log-table-container' ).fadeIn();
                } else {
                    alert( response.data );
                }
            }
        });
    }

    self.drawChart = function() {
        var barChartData = JSON.parse( '{"labels":["January","February","March","April","May","June","July"],"datasets":[{"label":"Dataset 1","backgroundColor":"rgb(255, 99, 132)","data":[80,-72,-72,81,96,27,54]},{"label":"Dataset 2","backgroundColor":"rgb(54, 162, 235)","data":[-3,-47,83,-86,-10,49,-70]},{"label":"Dataset 3","backgroundColor":"rgb(75, 192, 192)","data":[22,38,-70,-70,-69,-5,-1]}]}' );
        var ctx = document.getElementById("canvas");
        window.myBar = new Chart(ctx, {
            type: 'bar',
            data: barChartData,
            options: {
                title:{
                    display:true,
                    text:"Chart.js Bar Chart - Stacked"
                },
                tooltips: {
                    mode: 'index',
                    intersect: false
                },
                responsive: true,
                scales: {
                    xAxes: [{
                        stacked: true,
                    }],
                    yAxes: [{
                        stacked: true
                    }]
                }
            }
        });
    }

    self.init = function() {
        self.getMailgunEvents();
        self.getMailgunLog();
        // self.drawChart();
    };

    self.init();

}

jQuery( document ).ready( function( $ ) {
    var dashboardInstance = new MailgunDashboard_Dashboard( $ );
});