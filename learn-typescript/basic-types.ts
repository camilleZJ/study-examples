let isDone: boolean = false;

let age: number = 20;
let hexLiteral: number = 0xf00d; //十六进制
let binaryNumer: number = 0b1111; //es6支持二进制和八进制写法
let octalLiteral: number = 0o744; //es6支持二进制和八进制写法

let firstName: string = "camille"; //单引号/双引号
let message: string = `hello ${firstName}, age is ${age}`; //模版字符串

//null、undefined--面试官常问的
//null、undefined是其他类型的子类型，所以undefined以赋值给类型为number的变量
let u: undefined = undefined;
let n: null = null;
let num: number = undefined; //number类型的变量可以赋值为undefined，但是不能是string等
//当指定了--strictNullChecks标记（鼓励尽可能地使用），null和undefined只能赋值给void和它们各自。想传入一个 string或null或undefined，可以使用联合类型string | null | undefined

//无法确定类型-any：任意类型，尽量使用确定类型，否则就丧失静态类型的检测特性
let notSure: any = 4; //any类型的变量可以赋值任何类型的值
notSure = "maybe it is string";
notSure = true;
//调用任何属性和方法都不会报错，但是没有任何提示，像是string类型的变量message当输入message.时会提示string的一些属性方法
notSure.myName;
notSure.getName();

//联合类型
let numberOrSting: number | string = 234;
numberOrSting = "abc";

//array
let arrOfNumbers: number[] = [1, 2, 3, 4]; //number[]:元素全是number的数组，里面元素不能出现其他类型
arrOfNumbers.push(5); //也只能push number类型的元素
let list: Array<number> = [1, 2, 3]; //等价于上面的arrOfNumbers的number[]

//javascript中有一些类型array like object=》类似数组，可以采用数组的一些写法及属性但是不是array，如下的arguments
function test() {
  //没有写出接受参数，直接用arguments获取
  console.log(arguments);
  //arguments.length、arguments[0]存在，但是不存在数组的方法如forEach
  //let arr:[] = arguments //会报错

  // let htmlCollection : HTMLCollection //typescript中都有定义
  //let htmlCollection : Node
}

//number[]、string[]等是把同一类型的数据放到了一起，那么不同类型的数据放在一起呢？any[]这样会丧失掉静态类型检查得优势
//如想要数组第一项是数字类型，第二项是字符串类型等=》Tuple元祖
let userInfo: [string, number] = ["camille", 20];
//多一项、少一项都会报错
// userInfo = ['camille2']
// userInfo = ['camille2', 10, 10]
//当访问一个越界的元素，会使用联合类型替代：
// userInfo[3] = 'world'; // OK, 字符串可以赋值给(string | number)类型
// console.log(userInfo[5].toString()); // OK, 'string' 和 'number' 都有 toString
// userInfo[6] = true; // Error, 布尔不是(string | number)类型
