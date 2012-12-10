set :application, "quest"
set :domain,      "#{application}.teclliure.net"
set :deploy_to,   "/home/marc/quest"
set :app_path,    "app"

set :repository,  "files:///home/marc/workspace/#{application}"
set :scm,         :git
# Or: `accurev`, `bzr`, `cvs`, `darcs`, `subversion`, `mercurial`, `perforce`, or `none`

set :model_manager, "doctrine"
# Or: `propel`

role :web,        domain                         # Your HTTP server, Apache/etc
role :app,        domain                         # This may be the same as your `Web` server
role :db,         domain, :primary => true       # This is where Symfony2 migrations will run

set :keep_releases,  3
set :deploy_via, :rsync_with_remote_cache

# Be more verbose by uncommenting the following line
# logger.level = Logger::MAX_LEVEL

