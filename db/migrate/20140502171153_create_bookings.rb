class CreateBookings < ActiveRecord::Migration
  def change
    create_table :bookings do |t|
      t.references :meal, index: true
      t.references :user, index: true
      t.boolean :vegetarian

      t.timestamps
    end
  end
end
