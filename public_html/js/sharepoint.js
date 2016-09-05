

function loadSPLibrary() {
    var hostweburl;
    var appweburl;

    //Get the URI decoded URLs. 
    hostweburl = decodeURIComponent(getQueryStringParameter("SPHostUrl"));
    appweburl = decodeURIComponent(getQueryStringParameter("SPAppWebUrl"));

    // Load the .js files using jQuery's getScript function. 
    $.getScript(
            hostweburl + "/_layouts/15/SP.RequestExecutor.js",
            continueExecution
            );

    // After the cross-domain library is loaded, execution 
    // continues to this function. 
    function continueExecution() {
        var executor;

        // Initialize your RequestExecutor object. 
        executor = new SP.RequestExecutor(appweburl);
        // You can issue requests here using the executeAsync method 
        // of the RequestExecutor object.
    }

    // Function to retrieve a query string value.  
    function getQueryStringParameter(paramToRetrieve) {
        var params = document.URL.split("?")[1].split("&");
        var strParams = "";

        for (var i = 0; i < params.length; i = i + 1) {
            var singleParam = params[i].split("=");
            if (singleParam[0] == paramToRetrieve)
                return singleParam[1];
        }
    }

}

function getFiles(folderServerRelativeUrl) {
    var files;
    var context = SP.ClientContext.get_current();
    var web = context.get_web();
    var folder = web.getFolderByServerRelativeUrl(folderServerRelativeUrl);
    files = folder.get_files();
    context.load(files);
    context.executeQueryAsync(Function.createDelegate(this, this.OnSuccess), Function.createDelegate(this, this.OnFailure));

    function OnSuccess()
    {
        var listItemEnumerator = files.getEnumerator();
        while (listItemEnumerator.moveNext()) {
            var fileUrl = listItemEnumerator.get_current().get_serverRelativeUrl();
        }
    }

    function OnFailure(sender, args) {
        alert("Failed. Message:" + args.get_message());
    }
    
    return files;
}