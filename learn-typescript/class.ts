//class三大特性：继承（子类有用父类的属性和方法）、封装（隐藏内部实现，直接调用即可）、多态（同一个方法的不同实现，如cat和dog有各自的eat方法）
//class访问修饰符：public、private（修饰的只能在本类中使用，子类和实例对象都不能使用）、protected（和private相似，但是子类可以使用）
//只读属性修饰符：readonly
//静态属性/方法修饰符：static，static是属于类的，调用不用实例化对象，直接类名调用
class Animal {
  public name: string;
  readonly name1: string;
  static categoies: string[] = ["mammal", "bird"];

  static isAnimal(a) {
    return a instanceof Animal;
  }

  constructor(name: string) {
    this.name = name;
  }

  run() {
    return `${this.name} is running`;
  }
}

console.log(Animal.categoies); //静态属性直接类名调用

const snake = new Animal("snake"); //new 生成实例对象
console.log(snake.run());

console.log(Animal.isAnimal(snake));

console.log(snake.name); //name若是private修饰则这三处取值、赋值都会报错
snake.name = "lily"; //readyonly 修饰的属性赋值会报错
console.log(snake.name);

class Dog extends Animal {
  //extends关键字实现继承
  bark() {
    return `${this.name} is barking`;
  }
}
const hapi = new Dog("hapi"); //子类没有constructor会直接调用父类的constructor
console.log(hapi.bark());
console.log(hapi.run());

class Cat extends Animal {
  constructor(name) {
    super(name); //子类重写constructor不许显示调用父类的constructor：super(name);
    console.log(this.name);
  }

  run() {
    //重写父类方法
    return "Meow，" + super.run(); //super.run()调用父类的run方法
  }
}

const maomao = new Cat("maomao");
console.log(maomao.run());
