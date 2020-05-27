#防止同一文件被多次引用
require 'compass/import-once/activate'

#normalize replace reset模块
require 'compass-normalize'

# Require any additional compass plugins here.

# Set this to the root of your project when deployed:
http_path = "/"
css_dir = "stylesheets"
sass_dir = "sass"
images_dir = "images"
javascripts_dir = "javascripts"
fonts_dir = "fonts"
environment = :development

#--------Configuration Properties----------

# You can select your preferred output style here (can be overridden via the command line):
# output_style = :expanded or :nested or :compact or :compressed   默认是expanded 每次修改命令端都要重新监听才会生效
output_style = :expanded

# To enable relative paths to assets via compass helper functions. Uncomment:
# relative_assets = true  引用资源时是否使用相对路径true是使用   不设置默认是绝对路径
if environment == :development
	relative_assets = true
end

# To disable debugging comments that display the original location of your selectors. Uncomment:
# line_comments = false   生成的css中是否含调试注释信息  false是关闭    默认development环境下是开启  production环境下是关闭

# If you prefer the indented syntax, you might want to regenerate this
# project again passing --syntax sass, or you can uncomment this:
# preferred_syntax = :sass
# and then run:
# sass-convert -R --from scss --to sass sass scss && rm -rf sass && mv scss sass


# environment = :development or :production
output_style = (environment == :production) ? :compressed : :expanded





#--------Configuration Functions----------

# Increament the deploy_version before every
# release to force cache busting. 不设置默认为修改时间的时间戳
#设置一
#asset_cache_buster :none
#设置二
#asset_cache_buster do |http_path , real_path | 
#	"v=1"
#end
#设置三
#deploy_version = 1
#asset_cache_buster do |http_path, file|
#  if file
#    file.mtime.strftime("%s")
#  else
#    "v=#{deploy_version}"
#  end
#end
#设置四
#asset_cache_buster do |path, file|
#  if file
#    pathname = Pathname.new(path)
#    modified_time = file.mtime.strftime("%s")
#    new_path = "%s/%s-%s%s" % [pathname.dirname, pathname.basename(pathname.extname), modified_time, pathname.extname]

#    {:path => new_path, :query => nil}
#  end
#end

#关掉合sprite图生成文件命名自动加buster
# Make a copy of sprites with a name that has no uniqueness of the hash.  同时生成带buster的命名和不带的两种图片，但是生成的css代码还是用带buster的图片
#on_sprite_saved do |filename|
#  if File.exists?(filename)
#    FileUtils.cp filename, filename.gsub(%r{-s[a-z0-9]{10}\.png$}, '.png')
#  end
#end
# Replace in stylesheets generated references to sprites
# by their counterparts without the hash uniqueness.  生成的css改成引用命名不带buster的图片
#on_stylesheet_saved do |filename|
#  if File.exists?(filename)
#    css = File.read filename
#    File.open(filename, 'w+') do |f|
#      f << css.gsub(%r{-s[a-z0-9]{10}\.png}, '.png')
#    end
#  end
#end

