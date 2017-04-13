class SubmittersController < ApplicationController
    before_action :authenticate_user!

    def index
        @submitters = Submitter.all
    end
    
    def show
        @submitter = Submitter.find(params[:id])
    end
end
