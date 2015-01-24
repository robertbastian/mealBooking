class UsersController < ApplicationController

    def show
        user = User.where(user: cookies[:user]).first
        render json: user
    end

    def isVegetarian
        user = User.where(user: cookies[:user]).first
        render json: user.vegetarian
    end

    def setVegetarian
        user = User.where(user: cookies[:user]).first
        user.vegetarian = params[:vegetarian]
        user.save
        render json: user.vegetarian
    end
end
