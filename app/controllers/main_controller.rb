class MainController < ApplicationController
    
    def index
        @me = User.where(user: cookies[:user]).first
    end
    
    def login
    end

    def loginProcessor
        cookies[:user] = params[:username]
        redirect_to("/")
    end

    def logout
        cookies.delete(:user)
        redirect_to("/")
    end

    def meth
        Meal.destroy_all
        Booking.destroy_all
        for offset in 0..5
            if Date.today.saturday?
                sunday = Date.commercial(Date.today.year, Date.today.cweek+offset-1, -1)
            else
                sunday = Date.commercial(Date.today.year, Date.today.cweek+offset-1, -1)
            end
            @week = (sunday.cweek - 16).to_s
            @me = User.where(user: cookies[:user]).first
            days = (0..5).to_a.map{|o| sunday + o}
            days.each {|day|
                for type in 1..3 
                    meal = Meal.new(date:day,kind:(if day.sunday? and type == 1 then 0 else type end),capacity:150,menu:'["Shepherd’s Pie","Lamb Curry","Rice / Roast Pots","Vegetable Lasagne V","Seasonal Vegetables","Eves Pudding"]')
                    meal.save
                    if Random.rand(2) == 1
                        for x in 0..Random.rand(4)
                            b = Booking.new(meal: meal,user: @me,vegetarian: true)
                            b.save
                        end
                    end
               end
            }
        end
        render json: Meal.all#.map {|m| m[:date]}
    end
end
