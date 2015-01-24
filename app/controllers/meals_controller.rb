class MealsController < ApplicationController

    def menu
        meal = Meal.find(params[:id])
        render json: meal.menu
    end

    def index
        offset = params[:offset].to_i
        if Date.today.saturday?
            sunday = Date.commercial(Date.today.year, Date.today.cweek+offset, -1)
        else
            sunday = Date.commercial(Date.today.year, Date.today.cweek+offset-1, -1)
        end
        @week = (sunday.cweek - 16).to_s
        @me = User.where(user: cookies[:user]).first
        days = (0..5).to_a.map{|o| sunday + o}
        #days.each {|day| Meal.create(date:day,capacity: 150,kind: 3)}
        @meals = days.map{|day| 
            meals = Meal.where(date: day).order(:kind)
            if meals.count > 0
                meals.map{|meal| 
                    [meal,Booking.where(meal:meal).count,Booking.where(meal:meal,user:@me).count]
                }
            else
                false
            end
        }
        if @meals.all? {|m| !m}
            render json: "end"
        end
        @meals = days.zip(@meals)
    end

    def list
        # Retrieves the meal
        id = params[:id].to_i
        meal = Meal.find(id)
        # Retrieves bookings for the meal
        bookings = Booking.where(meal: meal)
        # Creates a list of usersnames from the bookings
        users = bookings.map {|booking| [booking.user.first_name,booking.user.last_name]}
        # Reduces the list to unique usernames and counts their bookings
        list = users.group_by{|name| name}.map{|name,occ| [name.first,name.last,occ.count]}
        render json: [["Brunch","Lunch","Informal Hall","Formal Hall"][meal[:kind]]+" – "+meal.date.strftime("%A, %b %d"),list]
    end

end
