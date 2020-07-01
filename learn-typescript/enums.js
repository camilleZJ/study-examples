var Direction;
(function (Direction) {
    Direction[Direction["Up"] = 0] = "Up";
    Direction[Direction["Down"] = 1] = "Down";
    Direction[Direction["Left"] = 2] = "Left";
    Direction[Direction["Right"] = 3] = "Right";
})(Direction || (Direction = {}));
console.log(Direction.Up); //0
console.log(Direction[0]); //Up
var Direction1;
(function (Direction1) {
    Direction1[Direction1["Up"] = 10] = "Up";
    Direction1[Direction1["Down"] = 11] = "Down";
    Direction1[Direction1["Left"] = 12] = "Left";
    Direction1[Direction1["Right"] = 13] = "Right";
})(Direction1 || (Direction1 = {}));
console.log(Direction1.Up); //10
console.log(Direction1[10]); //Up
console.log(Direction1.Left); //12
console.log(Direction1[12]); //Left
var Direction2;
(function (Direction2) {
    Direction2["Up"] = "Up";
    Direction2["Down"] = "Down";
    Direction2["Left"] = "Left";
    Direction2["Right"] = "Right";
})(Direction2 || (Direction2 = {}));
console.log(Direction2.Up); //Up
console.log(Direction2["Up"]); //Up
//enum使用：简单字符串比较
var value = "Up"; //某些操作返回的value
if (value === Direction2.Up) {
    console.log("go Up");
}
if (value === "Up" /* Up */) {
    console.log("go Up");
}
//编译出来的js上面的判断就是：
// if (value === "Up") {
//   //Direction3.Up直接翻译为"Up"
//   console.log("go Up");
// }
