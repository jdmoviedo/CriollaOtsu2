
function initEmailAutocomplete(arrayIds){
    arrayIds.forEach(element => {
        $("#"+element).emailautocomplete({
            suggClass: "eac-sugg",
	        domains: [
                "yahoo.com",
                "yahoo.es",
                "hotmail.com",
                "live.com",
                "msn.com",
                "hotmail.es",
                "outlook.com",
                "outlook.es",
                "gmail.com",
                "aol.com",
                "mac.com",
                "facebook.com",
                "verizon.net",
                "icloud.com",
                "YAHOO.COM",
                "YAHOO.ES",
                "HOTMAIL.COM",
                "LIVE.COM",
                "MSN.COM",
                "HOTMAIL.ES",
                "OUTLOOK.COM",
                "OUTLOOK.ES",
                "GMAIL.COM",
                "AOL.COM",
                "MAC.COM",
                "FACEBOOK.COM",
                "VERIZON.NET",
                "ICLOUD.COM"
            ]
        });
    });
}