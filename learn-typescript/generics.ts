//-----函数中的泛型-----
function echo<T>(arg: T): T {
  //<T>T是泛型名称，叫什么都可以，习惯于常用T=》创建了一个占位符T，一个神秘的变量，什么类型不确定
  //泛型相当于一个占位符或变量，使用时可以把定义好的变量像参数一样传入，之后原封不变的输出
  return arg;
}

const str1: string = "str";
const res = echo(123);
const res2 = echo(str1); //定义了类型
const res3 = echo("abc"); //未定义类型，采用类型推论
const res4 = echo(true);

//传入多个值的泛型
function swap<T, U>(tuple: [T, U]): [U, T] {
  return [tuple[1], tuple[0]];
}
const result1 = swap(["string", 123]);

// function echoWithArr<T>(arr: T): T {
function echoWithArr<T>(arr: T[]): T[] {
  //T[]:如number[]、string[]
  console.log(arr.length); //传入的参数不确定类型，所以不知道有没有length属性，此处会报错,所以修改为T[]
  return arr;
}
const arrs = echoWithArr([1, 2, 3]);

//问题：T[]只能传入数组，那么想要传入其他类型如object、string等=》那么只能传入据有length属性的类型=》对泛型进行约束
interface IWithLength {
  length: number;
}
function echoWithLength<T extends IWithLength>(arg: T): T {
  console.log(arg.length);
  return arg;
}
const str2 = echoWithLength("str");
const obj = echoWithLength({ length: 10 });
const arr1 = echoWithLength([1, 2, 3]);

//-----class中的泛型-----
//队列：先进先出
// class Queue {
//   private data = [];
//   push(item) {
//     this.data.push(item);
//   }

//   pop() {
//     this.data.shift();
//   }
// }
// const queue = new Queue();
// queue.push(1);
// queue.push("str");
// console.log(queue.pop().toFixed());
// console.log(queue.pop().toFixed());
//问题：toFixed是数字类型才有的方法，把 Number 四舍五入为指定小数位数的数字，参数：保留的小数位数。但是pop出的有string类型的值，若是限定：push(item: number)  pop():number也可以解决
//泛型解决：
class Queue1<T> {
  private data = [];
  push(item: T) {
    return this.data.push(item);
  }

  pop(): T {
    return this.data.shift();
  }
}

const queue1 = new Queue1<number>();
queue1.push(1);
console.log(queue1.pop().toFixed());
const queue2 = new Queue1<string>();
queue2.push("str");
console.log(queue2.pop().length);

//-----接口interface中的泛型-----
interface KeyPair<T, U> {
  key: T;
  value: U;
}
let kp1: KeyPair<number, string> = { key: 123, value: "haha" };
let kp2: KeyPair<string, number> = { key: "test", value: 123 };

let arr: number[] = [1, 2, 3];
let arrTwo: Array<number> = [2, 3, 4]; //数组里的泛型


interface IPlus {
  (a: number, b: number): number;
}
interface IPlus1<T> {
  (a: T, b: T): T;
}
function plus(a: number, b: number): number {
  return a + b;
}
function connect(a: string, b: string): string {
  return a + b;
}
const a: IPlus = plus; //函数表达式，注意此时interface返回值类型时':'而不是'=>'
const b: IPlus1<number> = plus;
const c: IPlus1<string> = connect;
