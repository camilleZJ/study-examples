enum Direction {
  Up,
  Down,
  Left,
  Right,
}
console.log(Direction.Up); //0
console.log(Direction[0]); //Up

enum Direction1 {
  Up = 10, //是number，以下从10开始递增
  Down,
  Left,
  Right,
}
console.log(Direction1.Up); //10
console.log(Direction1[10]); //Up
console.log(Direction1.Left); //12
console.log(Direction1[12]); //Left

enum Direction2 {
  Up = "Up", //是字符床，那么就不能像number那样，后面的元素必须设置初始值
  Down = "Down",
  Left = "Left",
  Right = "Right",
}
console.log(Direction2.Up); //Up
console.log(Direction2["Up"]); //Up

//enum使用：简单字符串比较
const value = "Up"; //某些操作返回的value
if (value === Direction2.Up) {
  console.log("go Up");
}

//常量枚举，定义前加const，编译出来的js是直接为常量，不会有enum的定义形式=》提升性能
const enum Direction3 {
  Up = "Up",
  Down = "Down",
  Left = "Left",
  Right = "Right",
}
if (value === Direction3.Up) {
  console.log("go Up");
}
//编译出来的js上面的判断就是：
// if (value === "Up") {
//   //Direction3.Up直接翻译为"Up"
//   console.log("go Up");
// }
