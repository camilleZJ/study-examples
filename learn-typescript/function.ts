//函数声明
function add(x: number, y: number, z: number = 10, d?: string): number {
  //可选参数只能放在最后，若是把y变为可选，z是必传参数=》函数定义的参数z会提示有问题,可选参数没有默认值，必填参数才会有默认值
  if (typeof z === "number") {
    return x + y + z;
  }

  return x + y;
}
let result = add(2, 3); //不带？的参数必传，否则会提示有问题

//函数表达式，三个类型：代表函数的变量是一个函数类型（没有指定类型，是typescript推断出的类型），参数有自己的类型、返回值有一个类型
const add1 = function (x: number, y: number, z: number = 10): number {
  if (typeof z === "number") {
    return x + y + z;
  }

  return x + y;
};
// const add2:string = add1 //报错，add1是函数类型不能赋值给string类型
const add2: (x: number, y: number, z?: number) => number = add1; //=>不是es6函数而是typescript中代表函数返回值，类型声明中不能使用赋值表达式形式的默认值，改为?可选

//如下typescript推断类型，在没有指定类型时typescript会推论
let str = "str";
// str = 123; //报错，提示：不能将类型“123”分配给类型“string”
