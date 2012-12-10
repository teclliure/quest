set :application, "quest"
set :domain,      "#{application}.teclliure.net"
set :deploy_to,   "/home/marc/quest"
set :app_path,    "app"

set :repository,  "file:///home/marc/workspace/#{application}"
set :scm,         :git
# Or: `accurev`, `bzr`, `cvs`, `darcs`, `subversion`, `mercurial`, `perforce`, or `none`

set :model_manager, "doctrine"
# Or: `propel`

role :web,        domain                         # Your HTTP server, Apache/etc
role :app,        domain                         # This may be the same as your `Web` server
role :db,         domain, :primary => true       # This is where Symfony2 migrations will run

set :keep_releases,  3
set :deploy_via, :rsync_with_remote_cache

set :shared_files,      ["app/config/parameters.yml",]
set :shared_children,  [app_path + "/logs", web_path + "/uploads", app_path + "/cache"]

set :use_sudo,   false

# Be more verbose by uncommenting the following line
# logger.level = Logger::MAX_LEVEL

default_run_options[:pty] = true
set :ssh_options, {:forward_agent => true}

before 'symfony:deploy:update_code', 'symfony:bootstrap'

=begin
set :use_composer, true

before 'symfony:composer:install', 'composer:copy_vendors'
before 'symfony:composer:update', 'composer:copy_vendors'

namespace :composer do
  task :copy_vendors, :except => { :no_release => true } do
    capifony_pretty_print "--> Copy vendor file from previous release"

    run "vendorDir=#{current_path}/vendor; if [ -d $vendorDir ] || [ -h $vendorDir ]; then cp -a $vendorDir #{latest_release}/vendor; fi;"
    capifony_puts_ok
  end
end

set :writable_dirs,     ["app/cache", "app/logs","app/bootstrap.php.cache"]
set :user,    "marc"
set :webserver_user,    "www-data"
set :permission_method, :chown
set :use_set_permissions, true

=end