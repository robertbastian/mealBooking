class Booking < ActiveRecord::Base
  belongs_to :meal
  belongs_to :user
  validates_presence_of :vegetarian, :meal, :user
end
