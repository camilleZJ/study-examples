declare var jQuery: (selector: string) => any 
//一般来说定义这个文件后，项目中的其他ts文件都能读取到这个声明，若是无法获取到直接在typescript配置文件中设置:"include": ["**/*"]编译当前文件夹下的所有文件
//问题：若是项目中引入大量第三方库，那么要写一堆文件声明=》解决：第三方声明文件-@types，如jQuery安装：npm install --save @types/jquery
//TypeSearch网站中搜索相应库看是否有文件声明，没有就需要自己写