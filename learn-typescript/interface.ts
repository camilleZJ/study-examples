//javascript中最常用的object，typescript的interface可以定义object中的数据类型：可以描述对象具有的属性、方法等，也可以对class的抽象

//用处1：----对object的形状进行描述----
interface IPerson {
  //接口名一般要求首字母要大写Person，且有的要求命名前加I：IPerson，代表是接口
  readonly id: number; //readonly表示只读的属性,对象定义时赋值后不可再更改
  name: string; //注意接口中属性采用的是分号而不是object中的逗号
  age?: number; //？表示这个属性可有可无，实现这个接口的变量就可以不定义这个属性，否则无？的变量都要实现
}

let camille: IPerson = {
  //必须实现接口中的所有属性，多了\少了都不可以
  id: 1234,
  name: "camille",
  //   age: 20,
};
// camille.id = 123 //会报错因为是只读属性

//----用处2：对class进行抽象----
//多个类之间具有共同的特性，继承很难来完成，将共有的特性抽离出来=》interface
//如下两个类Car和Cellphone，都有switchRadio方法，通过继承需要找一个共有的父类，但是两个class的switchRadio有各自的特性，所以用继承很难实现
interface Radio {
  switchRadio(triggerL: boolean): void; //接口了只定义方法不实现
}

interface Battery {
  checkBatteryStatus(): void;
}

interface RadioWithBattery extends Radio, Battery {
  //接口之间可以继承,而且可以多继承
}

class Car implements Radio {
  switchRadio() {}
}

// class Cellphone implements Radio, Battery { //单extends多implements，多个接口逗号分隔
class Cellphone implements RadioWithBattery {
  switchRadio() {}

  checkBatteryStatus() {}
}
