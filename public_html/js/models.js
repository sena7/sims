function Type() {


}


function Item() {
    this.order;
    this.name;
    this.type;
}

function Enum(constants) {
    for (var i in constants) {
        this[constants[i]] = i;
    }

}

function DueDate(name, date, category, display) {
    this.name = name;
    this.date = date;
    this.category = category;
    this.display = display;
}
function Category(name, description) {
    this.name = name;
    this.description = description;
}

function SpPath(name, path) {
    this.name = name;
    this.path = path;
}

//var itemType = Object.freeze("image", "html","webpage");

function Configuration(init) {
    if (init === true) {
        this.categoryList = [
            new Category('dateCategory A', 'example category'),
            new Category('dateCategory B', 'example category'),
            new Category('dateCategory c', 'example category')
        ];

        this.dateList = [
            new DueDate('system A', new Date(2016 - 01 - 01), this.categoryList[0], false),
            new DueDate('system B', new Date(2017 - 02 - 01), this.categoryList[1], false),
            new DueDate('system C', new Date(2018 - 03 - 01), this.categoryList[2], false)
        ];
        this.spList = [];

    } else {



        this.dateList = [];
        this.categoryList = [];
        this.spList = [];


    }
}


function getModel(name) {
    switch (name) {
        case 'dates':
            return new DueDate();
            break;
        case 'category':
            return new Category();
            break;
        case 'sharepoint':
            return new SpPath();

    }
}

function TimeDHMS(jsDate) {
    var dateMs = jsDate.getTime();

    var remainMs = dateMs - Date.now();
    //console.log(remainMs);

    var ms = 1;
    var s = ms * 1000;
    var m = s * 60;
    var h = m * 60;
    var d = h * 24;




    this.day = Math.floor(remainMs / d);
    this.hour = Math.floor((remainMs % d) / h);
    this.minute = Math.floor(((remainMs % d) % h) / m);
    this.second = Math.floor((((remainMs % d) % h) % m) / s);

    //console.log(this.day, this.hour, this.minute, this.second);


}



