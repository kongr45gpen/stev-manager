class User < ApplicationRecord
  # Include default devise modules. Others available are:
  # :confirmable, :lockable, :timeoutable and :omniauthable,
  # :registerable, :recoverable, :validatable
  devise :database_authenticatable,
         :rememberable, :trackable
end
