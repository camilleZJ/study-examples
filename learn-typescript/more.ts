//-----type alias-类型别名-----
interface IPlus {
  (a: number, b: number): number;
}
type PlusType = (a: number, b: number) => number; //类型别名
function sum(x: number, y: number): number {
  return x + y;
}
const sum2: IPlus = sum;
const sum3: PlusType = sum;

//类型别名常用于联合类型：|
type NameResolver = () => string; //函数类型
type NameOrResolver = string | NameResolver;
function getName(n: NameOrResolver): string {
  if (typeof n === "string") {
    return n;
  } else {
    return n();
  }
}

//-----type assertion类型断言-----
//注意类型断言不是类型转换，如下不可以<boolean>input
function getLength(input: string | number): number {
  //   const str = input as String; //把输入的断言为字符串，所以就可以str.length，若输入的不是字符串不会报错，只是不存在
  //   if (str.length) {
  //     return str.length;
  //   } else {
  //     const number = input as Number;
  //     return number.toString().length;
  //   }

  if ((<string>input).length) {
    return (<string>input).length;
  } else {
    return input.toString().length;
  }
}


//-----声明文件-----
//使用第三方库，如script标签引入jQuery，那么之后在代码中就可以直接使用$或jQuery，如jQuery("#root")
//但是在Typescript中直接使用会报错，需要声明
declare var jQuery: (selector: string) => any 
//一般会把这些声明单独放入一个文件，命名:[name].d.ts，即以.d.ts结尾命名
