class CreateMeals < ActiveRecord::Migration
  def change
    create_table :meals do |t|
      t.date :date
      t.decimal :type
      t.string :name
      t.string :menu
      t.decimal :capacity

      t.timestamps
    end
  end
end
