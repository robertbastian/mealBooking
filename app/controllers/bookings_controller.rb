class BookingsController < ApplicationController
    
    def book
        me = User.where(user: cookies[:user])
        booking = Booking.create(meal: params[:id],user: me, vegetarian: me.vegetarian)
        render json: (booking.save)
    end

end
