var MailgunDashboard_Settings = function( $ ) {

    var self = this;

    $( document ).on( 'change', '.js-mgd-mailgun_settings_source', function() {
        self.maybeDisableLocalMailgunSettings();
    });

    self.maybeDisableLocalMailgunSettings = function() {
        var mailgunSettingsSourceStatus = $( '.js-mgd-mailgun_settings_source' ).prop( 'checked' );
        $( '.js-mgd-mailgun_api_key' ).prop( 'readonly', mailgunSettingsSourceStatus );
        $( '.js-mgd-mailgun_domain' ).prop( 'readonly', mailgunSettingsSourceStatus );
    };

    self.init = function() {
        self.maybeDisableLocalMailgunSettings();
    };

    self.init();

}

jQuery( document ).ready( function( $ ) {
    var settingsInstance = new MailgunDashboard_Settings( $ );
});