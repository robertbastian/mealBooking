class ApplicationController < ActionController::Base
  # Prevent CSRF attacks by raising an exception.
  # For APIs, you may want to use :null_session instead.
  protect_from_forgery with: :exception
  before_filter :check_session
  skip_before_filter :check_session, :only => [:login,:loginProcessor]

  def check_session
    #sleep 1.5 #latency
    if not cookies.key?("user")
        redirect_to("/login")
    end
  end
end
