set :application, "quest"
set :domain,      "#{application}.teclliure.net"
set :deploy_to,   "/home/marc/quest"
set :app_path,    "app"
set :clear_controllers, false

set :assets_symlinks,       true
set :assets_relative,       true

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

set :shared_files,      ["app/config/parameters.yml","web/app_dev.php"]
set :shared_children,  [app_path + "/logs", web_path + "/uploads", app_path + "/cache"]

set :use_sudo,   false

set :use_composer,   true
set :composer_bin,   "/usr/local/bin/composer.phar"

# Be more verbose by uncommenting the following line
# logger.level = Logger::MAX_LEVEL

default_run_options[:pty] = true
set :ssh_options, {:forward_agent => true}


before "deploy" do
    run "#{try_sudo} sh -c 'rm -rf #{latest_release}/#{cache_path}/*'"
end

# after "deploy:create_symlink" do
after "deploy" do
    run "#{try_sudo} cd #{current_path} && #{php_bin} #{build_bootstrap}"
    run "#{try_sudo} chmod -R a+rw #{current_path}/#{cache_path}"
    run "#{try_sudo} chmod a+rw #{current_path}/app/bootstrap.php.cache"
end

# after "deploy", "deploy:cleanup"