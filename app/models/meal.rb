class Meal < ActiveRecord::Base
    validates_presence_of :date, :capacity,:kind
end
