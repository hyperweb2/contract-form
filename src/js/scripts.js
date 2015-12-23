var Hw2CF = Hw2CF || {}; // create namespace

Hw2CF.resetSignature = function (id) {
    $(id).jSignature("reset");
};

Hw2CF.initSignature = function (prefix) {
    var id = "#" + prefix + "_box";
    var div = $(id);
    if (div.length == 0)
        return false;

    div.jSignature();
    Hw2CF.resetSignature(id);

    var data = $("#" + prefix).val();
    // reimporting the data into jSignature.
    // import plugins understand data-url-formatted strings like "data:mime;encoding,data"
    data && div.jSignature("setData", data);
    return true;
};


Hw2CF.submitSign = function (prefix) {
    var datapair = $("#" + prefix + "_box").jSignature("getData", "base30");
    $("#" + prefix).val("data:" + datapair.join(","));
};

